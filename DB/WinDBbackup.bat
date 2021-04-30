@echo off
echo EXPORT DB CON GIT PUSH
echo.
pause
echo export db
set path=D:\software\xampp2\mysql\bin
mysqldump -u backup -pbackup biblio_plus > export.sql
echo commit
set path=C:\Program Files\Git\bin
git add export.sql
git commit -m "auto-export DB"
echo push
git push
echo OK
pause