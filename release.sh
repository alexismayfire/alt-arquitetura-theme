#!/bin/bash

function show_help {
    echo -e "Defina um parâmetro válido para rodar o script:\n"
    echo -e "./release.sh current"
    echo -e "-- Retorna a versão atual do código\n"
    echo -e "./release.sh version VERSION"
    echo -e "-- Cria uma nova release, sendo VERSION uma tag para o git. Ex.: 1.0.0"
}

function execute {
    case $1 in
        "current")
            git describe --tags;;
        "version")
            shift
            if [ "$1" != "" ]; then
                sed -i "4s/.*/Version: $1/" style.css
                sed -i "3s/.*/define( THEME_VERSION, '$1');/" functions.php
                git add functions.php style.css
                git commit -m "Release da versão $1"
                git tag v$1
                rm -f *.zip
                mv css/main.css css/critical.css
                zip -qr theme-v$1.zip ./core ./css ./dist ./fonts ./template-parts *.css *.php *.png
                echo -e "Versão $1 criada com sucesso, tag do git adicionada."
                echo -e "Um novo arquivo ZIP, contendo o tema pronto para adicionar ao WordPress, foi adicionado ao projeto."
                echo -e "O upload deve ser feito separadamente no Github."
                echo -e "Quando estiver pronto, faça push da nova tag rodando o comando:"
                echo -e "git push origin v$1"
            else
                echo -e "O argumento VERSION é obrigatório"
            fi;;
        *)
            echo -e "Argumento inválido: $1"
            show_help
        esac
}

if [ "$*" != "" ]; then
    execute "$1" "$2"
else
    show_help
fi
