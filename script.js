class Pizza {
    constructor(type, size) {
        this.pizzaTypes = {
            'Маргарита': { price: 500, calories: 300 },
            'Пепперони': { price: 800, calories: 400 },
            'Баварская': { price: 700, calories: 450 }
        };
        
        this.sizes = {
            'Большая': { price: 200, calories: 200 },
            'Маленькая': { price: 100, calories: 100 }
        };
        
        this.toppings = {
            'сливочная моцарелла': { price: 50, calories: 20 },
            'сырный борт': { price: null, calories: 50 },
            'чедер и пармезан': { price: null, calories: 50 }
        };
        
        this.type = type;
        this.size = size;
        this.addedToppings = new Set();
    }
    
    addTopping(topping) {
        if (!this.toppings[topping]) return;
        this.addedToppings.add(topping);
    }
    
    removeTopping(topping) {
        this.addedToppings.delete(topping);
    }
    
    getToppings() {
        return Array.from(this.addedToppings);
    }
    
    getType() {
        return this.type;
    }
    
    getSize() {
        return this.size;
    }
    
    calculatePrice() {
        let totalPrice = this.pizzaTypes[this.type].price + this.sizes[this.size].price;
        
        for (let topping of this.addedToppings) {
            let toppingPrice = this.toppings[topping].price;
            
            if (toppingPrice === null) {
                toppingPrice = this.size === 'Большая' ? 300 : 150;
            }
            
            totalPrice += toppingPrice;
        }
        
        return totalPrice;
    }
    
    calculateCalories() {
        let totalCalories = this.pizzaTypes[this.type].calories + this.sizes[this.size].calories;
        
        for (let topping of this.addedToppings) {
            totalCalories += this.toppings[topping].calories;
        }
        
        return totalCalories;
    }
}

function getSelectedType() {
    return document.querySelector('#typeGroup .pill.active').dataset.value;
}

function getSelectedSize() {
    return document.querySelector('#sizeGroup .pill.active').dataset.value;
}

function getSelectedToppings() {
    return [...document.querySelectorAll('.topping-label input:checked')]
        .map(el => el.value);
}

function calculate() {
    const pizza = new Pizza(
        getSelectedType(),
        getSelectedSize()
    );

    getSelectedToppings().forEach(t => pizza.addTopping(t));

    const price = pizza.calculatePrice();
    const calories = pizza.calculateCalories();

    document.getElementById("result").textContent =
        `Добавить в корзину за ${price} ₽ (${calories} Ккал)`;
}

document.querySelectorAll('#typeGroup .pill').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('#typeGroup .pill')
            .forEach(b => b.classList.remove('active'));
        
        btn.classList.add('active');
        
        document.querySelectorAll('.pizza-img').forEach(img => img.classList.remove('active'));
        document.getElementById('img-' + btn.dataset.value).classList.add('active');
        
        calculate();
    });
});

const stage = document.querySelector('.pizza-stage');

document.querySelectorAll('#sizeGroup .pill').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('#sizeGroup .pill')
            .forEach(b => b.classList.remove('active'));
        
        btn.classList.add('active');
        
        const isLarge = btn.dataset.value === 'Большая';
        stage.style.transform = `scale(${isLarge ? 1.18 : 1})`;
        
        calculate();
    });
});

document.querySelectorAll('.topping-label input')
    .forEach(ch => ch.addEventListener('change', calculate));

calculate();