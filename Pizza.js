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
        
        if (!this.pizzaTypes[type]) {
            throw new Error(`Пицца "${type}" не найдена`);
        }
        if (!this.sizes[size]) {
            throw new Error(`Размер "${size}" не найден`);
        }
    }
    
    addTopping(topping) {
        if (!this.toppings[topping]) {
            throw new Error(`Добавка "${topping}" не найдена`);
        }
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
                if (this.size === 'Большая') {
                    toppingPrice = 300;
                } else if (this.size === 'Маленькая') {
                    toppingPrice = 150;
                }
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
    
    getInfo() {
        return {
            type: this.type,
            size: this.size,
            toppings: this.getToppings(),
            price: this.calculatePrice(),
            calories: this.calculateCalories()
        };
    }
}
