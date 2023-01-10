//Récupère l'élément root
var r = document.querySelector(':root');

//Récupère le nombre de colonnes
document.addEventListener('DOMContentLoaded', function() {
    var gridColumns = document.querySelector('.gridColumns');
    var columnsNumber = gridColumns.dataset.columnsNumber;
});

//Créer la nouvelle valeur de grid-template-columns du CSS
for (i=0 ; i<columnsNumber ; i++)
{
    gridTemplateColumnsValue+='auto ';
}

//Affecte la nouvelle valeur de grid-template-columns du CSS
r.style.setProperty('--numberGridColumns', gridTemplateColumnsValue);

