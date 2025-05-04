import React, { useState } from "react";
import Navbar from "./layouts/Navbar";
import { OpenContext } from "./contexts/OpenContext";
import Home from "./components/Home";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";

const App = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [isHomeOpen, setIsHomeOpen] = useState(false);
  const [isListingOpen, setIsListingOpen] = useState(false);
  const [isPageOpen, setIsPageOpen] = useState(false);
  const [isBlogOpen, setIsBlogOpen] = useState(false);
  const [isThemesOpen, setIsThemesOpen] = useState(false);

  return (
    <Router>
      <OpenContext.Provider
        value={{
          isOpen,
          setIsOpen,
          isHomeOpen,
          setIsHomeOpen,
          isListingOpen,
          setIsListingOpen,
          isPageOpen,
          setIsPageOpen,
          isBlogOpen,
          setIsBlogOpen,
          isThemesOpen,
          setIsThemesOpen,
        }}
      >
        <Navbar />
        <Routes>
          <Route path="/" element={<Home />} />
        </Routes>
      </OpenContext.Provider>
    </Router>
  );
};

export default App;
