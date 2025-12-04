// ЗАДАНИЕ 3

function spinWords(str) {

  return str
    .split(' ') //разбиваем строку на слова
    .map(word => {
      //если слово состоит из 5 или более букв - переворачиваем его
      if (word.length >= 5) {
        return word.split('').reverse().join('');
      }
      
      return word;//иначе возвращаем как есть
    })
    .join(' '); //собираем слова обратно в строку
}


const result1 = spinWords( "Привет от Legacy" )
console.log(result1) // тевирП от ycageL

const result2 = spinWords( "This is a test" )
console.log(result2) // This is a test