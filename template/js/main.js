// проверка форм при попытке отправить данные
function checkForm(form)
{ 
    var el, // элемент
        value; // Значение

    // массив ошибок
    var errorList = [];

    // транскрипция ошибок
    var errorText = { 
                    1: "Не верно заполнено поле 'Имя' (минимум 2 символа)",
                    2: "Не верно заполнено поле 'E-mail'",
                    3: "Не верно заполнено поле 'Пароль' (минимум 6 символов)",
                    }

    // регулярные выражения для проверки введённых данных
    var exp_reg = { 
                    1: '[a-zA-Zа-яА-Я_-]{2,50}',
                    2: '[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}',
                    3: '[-._a-zа-я0-9]{6,50}',
                  }

    // прогоняем все елементы формы и, в зависимости от имени, проверяем 
    for (var i = 0; i < form.elements.length; i++)
    { 
        el = form.elements[i];
        value = el.value;

        switch (el.name)
        {
            case "name":
                // если значение не совпадает с регулярным выражением - записываем ошибку в массив
                if (value.match(exp_reg[1]) == null)
                    errorList.push(1);
                break;

            case "email":
                if (value.match(exp_reg[2]) == null)
                    errorList.push(2);
                break;

            case "password":
                if (value.match(exp_reg[3]) == null)
                    errorList.push(3);
                break;

            default:
                break;
        }
    }

    // итоговая проверка на наличие ошибок в массиве ошибок
    if (!errorList.length)
    	return true;

    //формирование вывода ошибок
    var errorMsg = "При заполнении формы допущены следующие ошибки:\n\n";
    for (i = 0; i < errorList.length; i++)
        errorMsg += errorText[errorList[i]] + "\n";

    alert(errorMsg);
    return false;
}

// загрузка изображений
function loadImages(form)
{   
    // визуальное изменение елементов DOM для поддержки интерактивности
	document.getElementById('loading').style.display = "block";
	document.getElementById('text').style.display = "block";
    document.getElementById('load_text').innerHTML = "Загрузка...";
    document.getElementById('errors_div').innerHTML = '';
	form.style.display = "none";

    // отправка данных на сервер (адрес, id input)
    postAjax('/loadImg', 'photoimg');
}

// успешная отправка данных
function success(data) 
{   
    // парсинг json массива
    var resp = JSON.parse(data);
    // блок фотографий
    var photos_div = document.getElementById('photos_div');
    // блок ошибок
    var errors = document.getElementById('errors_div');


    // проверка на наличие ошибок
    if (resp['error'] == 0)
    {
        // генерация и вывод html-кода загруженной фотографии
        var img = getUploadedImg(resp);
        photos_div.insertBefore(img, photos_div.firstChild);
    }
    else
    {
        // генерация и вывод ошибки
        var error = getError(resp);
        errors.appendChild(error);
    }
    
    // возврашение DOM елементов к исходному состоянию
    document.getElementById('loading').style.display = "none";
    document.getElementById('imageform').style.display = "block";
    document.getElementById('text').style.display = "block";
    document.getElementById('imageform').elements[0].value = '';
    document.getElementById('load_text').innerHTML = "Выберите фотографии для загрузки:";
}

// ошибка отправки
function error(status)
{
    alert('Error: ' + status + '. Перезагрузите страницу.');
}

// асинхронная отправка данных формы (по одному файлу, во избежание переполнения отправляемого массива)
function postAjax(url, id_el)
{   
    // input с загружаемыми файлами
    var input = document.getElementById(id_el);

    // проверяем массив файлов в файловом инпуте
    if(input.files.length > 0)
    {
        // проход по каждому файлу
        for(var i = 0; i < input.files.length; i++)
        {
            var formData = new FormData();
                file = input.files[i];
                xhr = new XMLHttpRequest();
            // заполняем объект FormData
            formData.append(input.name, file);

            // готовим ajax запрос
            xhr.open('POST', url);
            xhr.setRequestHeader('X-FILE-NAME', file.name);

            xhr.onreadystatechange = function(e)
            {
                if(e.target.readyState == 4)
                {
                    if(e.target.status == 200)
                    {
                        // успешно отправили файл
                        success(e.target.responseText);
                        return;
                    }
                    else
                    {
                        // произошла ошибка
                        error(e.status);
                    }
                }
            }
            // отправка данных на сервер
            xhr.send(formData);                      
        }
    }
}

// создание DOM элементов
function create(name, attributes)
{   
    // создание элемента
    var el = document.createElement(name);

    // добавление аттрибутов
    if (typeof attributes == 'object')
    {
        for (var i in attributes)
        {
            el.setAttribute(i, attributes[i]);

            // изменение регистра первой буквы для IE 
            if (i.toLowerCase() == 'class')
                el.className = attributes[i];

            else if (i.toLowerCase() == 'style') 
                el.style.cssText = attributes[i];
        }
    }

    // добавление содержимого елемента (текст, другие элементы)
    for (var i = 2;i < arguments.length; i++)
    {
        var val = arguments[i];
        if (typeof val == 'string')
            { 
                val = document.createTextNode(val)
            };

        el.appendChild( val );
    }

    return el;
}

// генерация html-кода с текстом ошибки
function getError(resp)
{
    // транскрипция ошибок
    var errorText = { 
                    1: "В Вашем альбоме уже имеется файл с таким именем.",
                    2: "Данный формат запрещен!",
                    3: "Ошибка! Файл слишком велик!",
                    4: "Не удалось загрузить файл. Не известная ошибка.",
                    5: "Ошибка! Нельзя загружать подобные файлы!",
                    }


    var fileName = '';                
    // неизвестный файл
    if (resp['error'] == 5)
        fileName =  '...';
    else
        fileName = resp['image']['name'];

    // получение текста ошибки
    var error_text = errorText[resp['error']]+"("+fileName+")";
    // создание span с текстом ошибки
    var error = create(
                      "span", 
                      {Class: 'imgList'},
                      error_text,

                      create("br")
                      );
    return error;
}

// генерирует html-код загруженной фотографии
function getUploadedImg(resp)
{
    var img = create( 
                    "a",
                    { href: '/'+resp['image']['path'], 
                    target: '_blank' }, 

                    create( "img", 
                            { height: 100, 
                              src: resp['image']['path'],
                              Class: 'images'}
                          ),  

                    create( 
                            "a", { href: '/deleteImg/'+ resp['image']['id']}, 

                            create( "img", 
                                    { height: 20, 
                                      src: '/template/images/close.png',
                                      Class: 'close'}
                                  )
                          )
                    );
    return img;
}