// ЗАДАНИЕ 5

function longestCommonSuffix(strs) {
    if (!strs || strs.length === 0) {
        return "";
    }

    let minLength = strs.reduce((min, s) => Math.min(min, s.length), Infinity);
    let commonSuffix = "";

    for (let j = 1; j <= minLength; j++) {
        const currentSuffix = strs[0].slice(strs[0].length - j);
        
        let isCommon = true;
        for (let i = 1; i < strs.length; i++) {
            if (!strs[i].endsWith(currentSuffix)) {
                isCommon = false;
                break;
            }
        }

        if (isCommon) {
            commonSuffix = currentSuffix;
        } else {
            break;
        }
    }

    if (commonSuffix.length >= 2) {
        return commonSuffix;
    } else {
        return "";
    }
}

const result1 = longestCommonSuffix(["цветок", "поток", "хлопок"]);
console.log(result1); 
// "ок"

const result2 = longestCommonSuffix(["собака", "гоночная машина", "машина"]);
console.log(result2); 
// ""

