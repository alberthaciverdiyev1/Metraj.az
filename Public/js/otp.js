 const inputs = document.querySelectorAll(".otp-inputs input");

  inputs.forEach((input, index) => {
    input.addEventListener("input", () => {
      input.value = input.value.replace(/[^0-9]/g, "").slice(0, 1);

      if (input.value && index < inputs.length - 1) {
        inputs[index + 1].focus(); 
      }
    });

    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !input.value && index > 0) {
        inputs[index - 1].focus();
      }
    });
  });