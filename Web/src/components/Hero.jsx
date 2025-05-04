import React from "react";

const Hero = () => {
  return (
    <div className="h-screen bg-fixed relative">
      <img
        src="src/assets/img/hero-img.jpg"
        alt="Living Room"
        className="size-full object-cover bg-fixed opacity-200"
      />

      <div class="absolute inset-0 bg-black opacity-50"></div>

      <div className="container absolute top-[50%]">
        <p className="text-white text-[68px] font-semibold">
          Your Way Home Starts Here
        </p>
        <p className="text-white text-lg">
          Thousands of luxury home enthusiasts just like you visit our website.
        </p>

        <div className="flex items-center space-x-2">
            
        </div>
      </div>
    </div>
  );
};

export default Hero;
