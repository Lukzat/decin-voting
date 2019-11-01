
# Spuštění projektu

- Pokud již běží, zastavit běžící container příkazem: `docker-compose down -v`

- Upravit práva na složce s CSV soubory: `chmod 0777 www/CSVHolder`

- Upravit práva na složce log: `chmod 0777 www/js/log`

- Spustit docker `docker-compose up -d`
