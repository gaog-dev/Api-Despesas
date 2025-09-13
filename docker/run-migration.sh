#!/bin/bash

echo "Aguardando o banco de dados ficar disponível..."
until docker exec api-despesas-db mysqladmin ping -h localhost --silent; do
    sleep 1
done

echo "Banco de dados disponível. Executando migrações..."
docker exec api-despesas-app php yii migrate