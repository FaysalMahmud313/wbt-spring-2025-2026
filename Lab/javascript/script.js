let a = 8;
let b = 10;

a = a + b;
b = a - b;
a = a - b;

console.log(`a = ${a} b = ${b}`);

function square(n) {
    console.log(`Calling for ${n}`);
}

for (i = 1; i <= 10; i++) {
    console.log(square(i));
}

let x = [10, 8, 6];
let y = null;

for (i = 0; i < 3; i++) {
    if (x[i] > y) {
        y = x[i];
    }
}
console.log(`Lrgest Number is: ${y}`)