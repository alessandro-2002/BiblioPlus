echo RESTORE DB CON GIT PULL
echo.
read -p "Press [Enter] key to restore backup..."
echo git pull
set path=C:\Program Files\Git\bin
git pull
echo DB restore
set path=D:\software\xampp2\mysql\bin
mysql -u backup -pbackup biblioteca_facile < export.sql
echo OK
read -p "Press [Enter] key to exit..."
