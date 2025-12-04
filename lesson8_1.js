// ЗАДАНИЕ 1

function pickPropArray(array, prop) {
  return array
    .filter(item => item.hasOwnProperty(prop))//filter для отбора объектов с нужным свойством
    .map(item => item[prop]);//map для получения значений этого свойства
}

console.log('=== ЗАДАНИЕ 1 ===');
const students = [
  { name: 'Павел', age: 20 },
  { name: 'Иван', age: 20 },
  { name: 'Эдем', age: 20 },
  { name: 'Денис', age: 20 },
  { name: 'Виктория', age: 20 },
  { age: 40 },
];

const result = pickPropArray(students, 'name');
console.log(result);
// [ 'Павел', 'Иван', 'Эдем', 'Денис', 'Виктория' ]

