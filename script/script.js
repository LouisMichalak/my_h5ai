$(document).on('click', '.displayButton', function () {
    $parent = $(this).parent();
    if ($parent.attr('class').indexOf('opennedDir') !== -1)
    {
        $parent.attr('class', 'closedDir');
    }
    else
    {
        $parent.attr('class', 'opennedDir');
    }
});
$('.before').click(function () {
    window.history.back();
});
$('.after').click(function () {
    window.history.forward();
});
function displayMainMenu () {
    $.get(
        'php/treeGen.php',
        {
            displayMenu: 'true',
            folder: getGetMethod(),
            name: getnameSort(),
            date: getdateSort(),
            size: getsizeSort()
        },
        function (data) {
            console.log(data);
            $('.linksMainMenu').html(data);
        },
        'text'
    );
}
function getGetMethod()
{
    let url = location.href;
    let getPos = url.indexOf('my_h5ai') + 'my_h5ai'.length;
    return(url.substr(getPos));
}
function getnameSort()
{
    if (window.localStorage.getItem('sortname') === null)
        var name = 'none';
    else
        var name = window.localStorage.getItem('sortname');
    return(name);
}
function getdateSort()
{
    if (window.localStorage.getItem('sortmodifDate') === null)
        var date = 'none';
    else
        var date = window.localStorage.getItem('sortmodifDate');
    return(date);
}
function getsizeSort()
{
    if (window.localStorage.getItem('sortfileSize') === null)
        var fileSize = 'none';
    else
        var fileSize = window.localStorage.getItem('sortfileSize');
    return(fileSize)
}
$('.name, .modifDate, .fileSize').click(function () {
    if ($(this).children('.modifier').html() === "")
    {
        $(this).children('.modifier').html("↘");
        window.localStorage.setItem('sort'+$(this).attr('class'), 'desc');
    }
    else if ($(this).children('.modifier').html() === "↘")
    {
        $(this).children('.modifier').html("↗");
        window.localStorage.setItem('sort'+$(this).attr('class'), 'asc');
    }
    else
    {
        $(this).children('.modifier').html("");
        window.localStorage.setItem('sort'+$(this).attr('class'), 'none');
    }
    displayMainMenu();
});