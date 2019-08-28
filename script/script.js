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
            $('.linksMainMenu').html(data);
			let icons = window.FileIcons;
			$('.linksMainMenu a').each(function (key, data){
				if($($('.linksMainMenu a .fileIcon')[key]).attr('class').indexOf('dir') === -1)
				{
					var fileName = $($('.linksMainMenu a .fileName')[key]).html();
					var class_name = icons.getClassWithColor(fileName) || 'gear-icon';
					$($('.linksMainMenu a .fileIcon')[key]).addClass(class_name);
				}
			});
        },
        'text'
    );
}
displayMainMenu();
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
$(document).on('click', '.linksMainMenu a', function(e){
	if($(this).attr('href').substr(-1) !== '/')
		e.preventDefault();
	else
		return;
	$.get(
		'php/treeGen.php',
		{
			displayModal: 'true',
			folder: $(this).attr('href').substr('/my_h5ai'.length)
		},
		function(data) {
			if (data.match(/<img.*?src="(.*?)"[^\>]+>/g) !== null &&
				data.match(/<img.*?src="(.*?)"[^\>]+>/g)[0] === data)
			{
				$('.modalDiv').html(data);
			}
			else
			{
				$('.modalDiv').html('<code class="modal">'+
									'<pre></pre></code>');
			}
			$('.modal pre').text(data);
			$('body').append('<div class="shadow"></div>');
			$('.shadow').animate({top: 0, opacity: 1}, 300);
			$('.modal').show();
			$('.modal').delay(300).animate({top: '10%'}, 300);
		},
		'text'
	);
});
$(document).on('click', function(e){
	if(e.target.className === 'shadow')
	{
		$('.modal').animate({top: '100%'}, 300);
		$('.shadow').delay(300).animate({left: '100%'}, 300, function() {
			$('.modalDiv').html('');
		});
	}
});
window.requestAnimationFrame = window.requestAnimationFrame ||
	window.mozRequestAnimationFrame ||
	window.webkitRequestAnimationFrame ||
	window.msRequestAnimationFrame;
let animationCounter = 0;
$(document).on('click','#searchBtn', function(){
	animationCounter++;
	if(animationCounter === 1)
	{
		$('.before, .after').fadeToggle();
		$(this).animate({left: '20%'});
		$('.moveButtons').prepend('<button class="cross">&#10007;</button>'+
								  '<input class="searchInput" type="text" placeholder="Dossier ou fichier">');
		$('.searchInput').animate({left: '52%'});
	}
	else
	{
		let value = $('.searchInput').val();
		$.get(
			'php/treeGen.php',
			{
				researchFiles: 'true',
				input: value
			},
			function(data)
			{
				$('.linksMainMenu').html(data);
			},
			'text'
		);
	}
});
$(document).on('click', '.cross', function(){
	animationCounter = 0;
	$(this).fadeTo(200, 0).queue(function(){
		$(this).remove();
	});
	$('.before, .after').fadeToggle();
	$('#searchBtn').removeAttr('style').queue(function(){
		$('#searchBtn').remove();
		$('.moveButtons').prepend('<span tabindex="0" id="searchBtn">🔍</span>');
	});
	$('.searchInput').animate({left: '150%'}).queue(function(){
		$('.searchInput').remove();
	});
});