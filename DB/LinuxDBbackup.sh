echo EXPORT DB CON GIT PUSH
echo.
read -p "Press [Enter] key to start backup..."
echo export db
mysqldump -u backup -pbackup biblioteca_facile > export.sql
echo commit
git add export.sql
git commit -m "auto-export DB"
echo push
git push
echo OK
read -p "Press [Enter] key to exit..."