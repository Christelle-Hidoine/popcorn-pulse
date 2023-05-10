if [ -z "$1" ]
then
      echo "\$1 is NULL : donne un code d'erreur"
else
    code=$1
    cd ../../templates
    if [[ -f "./bundles/TwigBundle/Exception/" ]]
    then
        cd bundles/TwigBundle/Exception
    else
        mkdir -p bundles/TwigBundle/Exception
        cd bundles/TwigBundle/Exception
    fi

    touch "error${code}.html.twig"
    echo "{% extends \"base.html.twig\" %}" >> "error${code}.html.twig"
    echo "{% block body %}" >> "error${code}.html.twig"
    echo "Ceci est la page d'erreur ${code}" >> "error${code}.html.twig"
    echo "{% endblock %}" >> "error${code}.html.twig"
fi