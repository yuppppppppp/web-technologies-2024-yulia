// ЗАДАНИЕ 2

function createCounter() {
  let count = 0;//локальная переменная, доступная только внутри замыкания
  
  return function () {
    count++;
    console.log(count);
  }
}


const counter1 = createCounter()
counter1() // 1
counter1() // 2

const counter2 = createCounter()
counter2() // 1
counter2() // 2
