
const textElement = document.getElementById('typing-text');
const text = "DETAIL YOUR CAR LIKE A PRO";

 let index = 0;

 function type() {
 textElement.textContent = text.substr(0, index);
 index++;

    if (index <= text.length) {
        setTimeout(type, 100); // Adjust the typing speed by changing the delay (in milliseconds)
    } else {
        index = 0; // Reset the index to start the typing animation again
        setTimeout(type, 2000); // Delay before restarting the typing animation (2000 milliseconds)
    }
 }

 setTimeout(type, 1500); // Delay the typing animation start for 1.5 seconds (1500 milliseconds)

 // Facts counter
 $('[data-toggle="counter-up"]').counterUp({delay: 10,time: 2000});
