document.addEventListener('DOMContentLoaded', function() {
    // Навигация по разделам
    const navLinks = document.querySelectorAll('nav ul li a');
    const sections = document.querySelectorAll('.section');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Удаляем активный класс у всех ссылок и разделов
            navLinks.forEach(l => l.classList.remove('active'));
            sections.forEach(s => s.classList.remove('active'));
            
            // Добавляем активный класс текущей ссылке
            this.classList.add('active');
            
            // Показываем соответствующий раздел
            const sectionId = this.getAttribute('href');
            document.querySelector(sectionId).classList.add('active');
            
            // Плавная прокрутка
            if (sectionId !== '#about') {
                const section = document.querySelector(sectionId);
                const headerHeight = document.querySelector('header').offsetHeight;
                const sectionPosition = section.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: sectionPosition,
                    behavior: 'smooth'
                });
            } else {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Загрузка работ из JSON
    loadWorks();

    // Фильтрация работ по категориям
    const categoryBtns = document.querySelectorAll('.category-btn');
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.getAttribute('data-category');
            filterWorks(category);
        });
    });
});

// Функция загрузки работ
function loadWorks() {
    fetch('works.json')
        .then(response => response.json())
        .then(data => {
            displayWorks(data.works);
            // Сохраняем работы для фильтрации
            window.allWorks = data.works;
        })
        .catch(error => console.error('Ошибка загрузки работ:', error));
}

// Функция отображения работ
function displayWorks(works) {
    const worksContainer = document.getElementById('works-container');
    worksContainer.innerHTML = '';
    
    works.forEach(work => {
        const workItem = document.createElement('div');
        workItem.className = 'work-item';
        workItem.setAttribute('data-category', work.category);
        
        workItem.innerHTML = `
            <a href="images/works/${work.image}" data-lightbox="works" data-title="${work.title} - ${work.description}">
                <img src="images/works/${work.image}" alt="${work.title}">
                <div class="work-info">
                    <h3>${work.title}</h3>
                    <p>${work.description}</p>
                </div>
            </a>
        `;
        
        worksContainer.appendChild(workItem);
    });
}

// Функция фильтрации работ
function filterWorks(category) {
    if (category === 'all') {
        displayWorks(window.allWorks);
    } else {
        const filteredWorks = window.allWorks.filter(work => work.category === category);
        displayWorks(filteredWorks);
    }
}

// Админ-панель
const adminBtn = document.getElementById('admin-btn');
const adminPanel = document.getElementById('admin-panel');
const closeAdmin = document.getElementById('close-admin');
const addWorkForm = document.getElementById('add-work-form');
const imageInput = document.getElementById('work-image');
const imagePreview = document.getElementById('image-preview');

// Пароль для доступа к админке (можно изменить)
const ADMIN_PASSWORD = "admin123";

// Открытие админ-панели
adminBtn.addEventListener('click', () => {
    const password = prompt('Введите пароль для доступа:');
    if (password === ADMIN_PASSWORD) {
        adminPanel.style.display = 'flex';
    } else if (password !== null) {
        alert('Неверный пароль!');
    }
});

// Закрытие админ-панели
closeAdmin.addEventListener('click', () => {
    adminPanel.style.display = 'none';
});

// Превью изображения
imageInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.innerHTML = `<img src="${e.target.result}" alt="Превью">`;
        }
        reader.readAsDataURL(file);
    }
});

// Добавление новой работы
addWorkForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const title = document.getElementById('work-title').value;
    const description = document.getElementById('work-description').value;
    const category = document.getElementById('work-category').value;
    const imageFile = imageInput.files[0];
    
    if (!imageFile) {
        alert('Пожалуйста, выберите изображение');
        return;
    }
    
    // Генерируем уникальное имя файла
    const fileName = `${Date.now()}-${imageFile.name}`;
    
    // Создаем объект новой работы
    const newWork = {
        title: title,
        description: description,
        image: fileName,
        category: category
    };
    
    // Добавляем работу в массив
    window.allWorks.push(newWork);
    
    // Сохраняем в JSON (это имитация, в реальном проекте нужен бэкенд)
    saveWorksToJson(newWork, fileName, imageFile);
    
    // Обновляем отображение работ
    filterWorks(document.querySelector('.category-btn.active').getAttribute('data-category'));
    
    // Очищаем форму
    this.reset();
    imagePreview.innerHTML = '';
    
    // Закрываем админ-панель
    adminPanel.style.display = 'none';
    
    alert('Работа успешно добавлена!');
});

// Функция загрузки изображения на сервер
async function uploadImage(file) {
    const formData = new FormData();
    formData.append('image', file);
    
    try {
        const response = await fetch('admin/upload.php', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${ADMIN_PASSWORD}`
            },
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Ошибка загрузки изображения');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Ошибка:', error);
        throw error;
    }
}

// Функция сохранения работы на сервере
async function saveWorkToServer(work) {
    try {
        const response = await fetch('api/works.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(work)
        });
        
        if (!response.ok) {
            throw new Error('Ошибка сохранения работы');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Ошибка:', error);
        throw error;
    }
}

// Обновляем обработчик формы
addWorkForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const title = document.getElementById('work-title').value;
    const description = document.getElementById('work-description').value;
    const category = document.getElementById('work-category').value;
    const imageFile = imageInput.files[0];
    
    if (!imageFile) {
        alert('Пожалуйста, выберите изображение');
        return;
    }
    
    try {
        // Показываем индикатор загрузки
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Загрузка...';
        
        // 1. Загружаем изображение
        const uploadResult = await uploadImage(imageFile);
        const fileName = uploadResult.filename;
        
        // 2. Сохраняем данные работы
        const newWork = {
            title: title,
            description: description,
            image: fileName,
            category: category
        };
        
        await saveWorkToServer(newWork);
        
        // 3. Обновляем список работ
        window.allWorks.push(newWork);
        filterWorks(document.querySelector('.category-btn.active').getAttribute('data-category'));
        
        // Очищаем форму
        this.reset();
        imagePreview.innerHTML = '';
        
        // Закрываем админ-панель
        adminPanel.style.display = 'none';
        
        alert('Работа успешно добавлена!');
    } catch (error) {
        alert('Произошла ошибка: ' + error.message);
    } finally {
        // Восстанавливаем кнопку
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Добавить работу';
    }
});