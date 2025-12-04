// ЗАДАНИЕ 4

function twoSum(nums, target) {
  const map = new Map(); //для хранения чисел и их индексов
  
  //проходим по всем числам массива
  for (let i = 0; i < nums.length; i++) {

    const targ = target - nums[i];
    
    //если такое число уже есть в Map, возвращаем индексы
    if (map.has(targ)) {
      return [map.get(targ), i];
    }
    
    map.set(nums[i], i); //текущее число и его индекс в Map
  }
  
  // Если пара не найдена, возвращаем пустой массив
  return [];
}


console.log(twoSum([2, 7, 11, 15], 9));

// Вход: nums = [2,7,11,15], target = 9
// Вывод: [0,1]
// Объяснение: Поскольку nums[0] + nums[1] == 9, мы возвращаем [0, 1].