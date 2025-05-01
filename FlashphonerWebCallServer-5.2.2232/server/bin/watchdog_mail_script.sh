TO=$1
SUBJECT=$2
TEXT=$3

echo "$TEXT" | mail -s "$SUBJECT" $TO
