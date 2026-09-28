#!/usr/bin/env bash
#
# Rapatrie les visuels Gelpaz pour un auto-hébergement (P2 de l'audit UX/UI).
#
# À exécuter sur un poste disposant d'un accès Internet, depuis la racine du site :
#
#     bash tools/fetch-media.sh
#
# Le script télécharge les variantes larges dans assets/images/media/, génère
# des versions WebP quand cwebp est disponible, puis affiche le bloc PHP à
# recopier dans includes/data.php ($images).
#
# Pourquoi ce script : l'environnement de développement de la refonte n'a pas
# d'accès sortant, les visuels sont donc encore servis depuis gelpaz.com.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUT="$ROOT/assets/images/media"
DATA="$ROOT/includes/data.php"

mkdir -p "$OUT"

echo "→ Lecture des URLs depuis includes/data.php"
mapfile -t URLS < <(grep -oE "https://gelpaz\.com/wp-content/uploads/[^']+" "$DATA" | sort -u)

if [ "${#URLS[@]}" -eq 0 ]; then
    echo "Aucune URL trouvée dans $DATA" >&2
    exit 1
fi

echo "→ ${#URLS[@]} visuel(s) à rapatrier"

declare -a LOCAL
for url in "${URLS[@]}"; do
    file="$(basename "$url")"
    # Les vignettes -525x328 sont remplacées par la variante large quand elle existe
    wide="${url/-525x328/-835x467}"
    target="$OUT/$file"

    if curl -fsSL "$wide" -o "$target" 2>/dev/null; then
        :
    elif curl -fsSL "$url" -o "$target" 2>/dev/null; then
        :
    else
        echo "  ! échec : $url" >&2
        continue
    fi

    if command -v cwebp >/dev/null 2>&1; then
        cwebp -quiet -q 82 "$target" -o "${target%.*}.webp" || true
    fi

    echo "  ✓ $file"
    LOCAL+=("$file")
done

echo
echo "→ Terminé : ${#LOCAL[@]} fichier(s) dans assets/images/media/"
echo
echo "Bloc à adapter dans includes/data.php (remplacez les URLs par ces chemins) :"
echo
for file in "${LOCAL[@]}"; do
    echo "    '/assets/images/media/$file',"
done
echo
echo "Variantes WebP générées : $(find "$OUT" -name '*.webp' | wc -l | tr -d ' ')"
echo "Pensez à vider le cache et à vérifier les pages Propriétés et Actualités."
