import { OpenContext } from "../contexts/OpenContext";
import { useContext } from "react";
import { Link, NavLink } from "react-router-dom";
import { BiLogoLinkedin } from "react-icons/bi";
import { FiInstagram, FiPhone } from "react-icons/fi";
import { IoMdClose } from "react-icons/io";
import { FaAngleDown, FaAngleUp, FaXTwitter } from "react-icons/fa6";
import { FaFacebookF } from "react-icons/fa";
import { CgMenu } from "react-icons/cg";
import { LuLayoutDashboard } from "react-icons/lu";

const Navbar = () => {
  const { isOpen, setIsOpen } = useContext(OpenContext);
  const { isHomeOpen, setIsHomeOpen } = useContext(OpenContext);
  const { isListingOpen, setIsListingOpen } = useContext(OpenContext);
  const { isPageOpen, setIsPageOpen } = useContext(OpenContext);
  const { isBlogOpen, setIsBlogOpen } = useContext(OpenContext);
  const { isThemesOpen, setIsThemesOpen } = useContext(OpenContext);

  const handleDropdown = (stateSetter) => {
    stateSetter((prevState) => !prevState);
  };

  return (
    <div className="h-20 w-full p-4 flex items-center justify-between border fixed relative top-0">
      <div className="w-[136px] h-[42px]">
        <img
          src="src/assets/img/logo.png"
          alt="Site logo"
          className="size-full object-cover"
        />
      </div>

      <nav className="hidden lg:block">
        <ul className="flex items-center justify-center space-x-4 h-[80px]">
          <li className="relative group h-full flex items-center justify-center">
            <NavLink
              to="/"
              className={({ isActive }) =>
                `flex items-center ${
                  isActive
                    ? "text-(--custom-color) font-bold"
                    : "text-(--custom-black)"
                }`
              }
            >
              Home <FaAngleDown className="ml-1.5" />
            </NavLink>

            {/* HOME DROPDOWN */}
            <ul className="flex-col space-y-3 bg-white shadow-sm rounded-lg py-6 px-4 absolute top-18 left-0 w-[250px] hidden opacity-0 group-hover:flex group-hover:opacity-100 overflow-hidden transition-all duration-300 ease-out z-10">
              <li>
                <NavLink
                  to="/home1"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Home 1
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/home2"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Home 2
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/home3"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Home 3
                </NavLink>
              </li>
            </ul>
          </li>

          <li className="relative group h-full flex items-center justify-center">
            <NavLink
              to="/listing"
              className={({ isActive }) =>
                `flex items-center ${
                  isActive
                    ? "text-(--custom-color) font-bold"
                    : "text-(--custom-black)"
                }`
              }
            >
              Listing <FaAngleDown className="ml-1.5" />
            </NavLink>

            {/* LISTING DROPDOWN */}
            <ul className="flex-col space-y-3 bg-white shadow-sm rounded-lg py-6 px-4 absolute top-18 left-0 w-[250px] hidden opacity-0 group-hover:flex group-hover:opacity-100 transition-all duration-300 ease-out z-10">
              <li>
                <NavLink
                  to="/layout"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Layout
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/feature"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Feature
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/listing-details"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Listing Details
                </NavLink>
              </li>
            </ul>
          </li>

          <li className="relative group h-full flex items-center justify-center">
            <NavLink
              to="/pages"
              className={({ isActive }) =>
                `flex items-center ${
                  isActive
                    ? "text-(--custom-color) font-bold"
                    : "text-(--custom-black)"
                }`
              }
            >
              Pages <FaAngleDown className="ml-1.5" />
            </NavLink>

            {/* PAGES DROPDOWN */}
            <ul className="flex-col space-y-3 bg-white shadow-sm rounded-lg py-6 px-4 absolute top-18 left-0 w-[250px] hidden opacity-0 group-hover:flex group-hover:opacity-100 transition-all duration-200 ease-out z-10">
              <li className="relative group/nested">
                <div className="flex items-center relative w-full">
                  <NavLink
                    to="/agents"
                    className={({ isActive }) =>
                      `flex items-center justify-between hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                        isActive
                          ? "text-(--custom-color)"
                          : "text-(--custom-black)"
                      } pr-10 w-full`
                    }
                  >
                    Agents
                  </NavLink>
                  <span className="absolute right-0 top-1/2 transform -translate-y-1/2">
                    <FaAngleDown className="ml-1.5" />
                  </span>
                </div>

                {/* AGENTS DROPDOWN */}
                <ul className="flex-col space-y-3 bg-white shadow-sm rounded-lg py-6 px-4 absolute -top-6 left-58 w-[250px] hidden opacity-0 group-hover/nested:flex group-hover/nested:opacity-100 transition-all duration-200 ease-out z-20">
                  <div className="absolute w-10 h-full -left-8 top-0"></div>
                  <li>
                    <NavLink
                      to="/agents."
                      className={({ isActive }) =>
                        `hover:text-(--custom-color) transition-all duration-300 ${
                          isActive
                            ? "text-(--custom-color)"
                            : "text-(--custom-black)"
                        }`
                      }
                    >
                      Agents.
                    </NavLink>
                  </li>

                  <li>
                    <NavLink
                      to="/agents-details"
                      className={({ isActive }) =>
                        `hover:text-(--custom-color) transition-all duration-300 ${
                          isActive
                            ? "text-(--custom-color)"
                            : "text-(--custom-black)"
                        }`
                      }
                    >
                      Agents Details
                    </NavLink>
                  </li>
                </ul>
              </li>

              <li>
                <NavLink
                  to="/agencies"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Agencies
                </NavLink>
              </li>

              <li>
                <NavLink
                  to="/career"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Career
                </NavLink>
              </li>
            </ul>
          </li>

          <li className="relative group h-full flex items-center justify-center">
            <NavLink
              to="/blog"
              className={({ isActive }) =>
                `flex items-center ${
                  isActive
                    ? "text-(--custom-color) font-bold"
                    : "text-(--custom-black)"
                }`
              }
            >
              Blog <FaAngleDown className="ml-1.5" />
            </NavLink>

            {/* LISTING DROPDOWN */}
            <ul className="flex-col space-y-3 bg-white shadow-sm rounded-lg py-6 px-4 absolute top-18 left-0 w-[250px] hidden opacity-0 group-hover:flex group-hover:opacity-100 transition-all duration-300 ease-out z-10">
              <li>
                <NavLink
                  to="/blog-list"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Blog List
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/blog-grid"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Blog Grid
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/blog-detail"
                  className={({ isActive }) =>
                    `hover:text-(--custom-color) transition-all duration-300 opacity-0 group-hover:opacity-100 ${
                      isActive
                        ? "text-(--custom-color)"
                        : "text-(--custom-black)"
                    }`
                  }
                >
                  Blog Detail
                </NavLink>
              </li>
            </ul>
          </li>

          <li>
            <NavLink
              to="/contact"
              className={({ isActive }) =>
                `flex items-center ${
                  isActive
                    ? "text-(--custom-color) font-bold"
                    : "text-(--custom-black)"
                }`
              }
            >
              Contact
            </NavLink>
          </li>
        </ul>
      </nav>

      <div className="flex items-center space-x-3">
        <div className="items-center space-x-2 hidden sm:flex">
          <FiPhone className="text-xl text-(--custom-color)" />
          <span className="text-(--custom-black) font-bold">
            (603) 555-0123
          </span>
        </div>

        {/* THEMESFLAT */}
        <div
          className="flex items-center space-x-3 cursor-pointer relative"
          onClick={() => handleDropdown(setIsThemesOpen)}
        >
          <div className="flex items-center justify-center border-[0.8px] border-[#ECECEC] rounded-md p-2">
            <svg
              width={24}
              height={24}
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M15.749 6C15.749 6.99456 15.3539 7.94839 14.6507 8.65165C13.9474 9.35491 12.9936 9.75 11.999 9.75C11.0044 9.75 10.0506 9.35491 9.34735 8.65165C8.64409 7.94839 8.249 6.99456 8.249 6C8.249 5.00544 8.64409 4.05161 9.34735 3.34835C10.0506 2.64509 11.0044 2.25 11.999 2.25C12.9936 2.25 13.9474 2.64509 14.6507 3.34835C15.3539 4.05161 15.749 5.00544 15.749 6ZM4.5 20.118C4.53213 18.1504 5.33634 16.2742 6.73918 14.894C8.14202 13.5139 10.0311 12.7405 11.999 12.7405C13.9669 12.7405 15.856 13.5139 17.2588 14.894C18.6617 16.2742 19.4659 18.1504 19.498 20.118C17.1454 21.1968 14.5871 21.7535 11.999 21.75C9.323 21.75 6.783 21.166 4.5 20.118Z"
                stroke="#2C2E33"
                strokeWidth="1.5"
                strokeLinecap="round"
                strokeLinejoin="round"
              />
            </svg>
          </div>

          <div className="items-center space-x-1.5 hidden md:flex">
            <span>Themesflat</span>
            <FaAngleDown />
          </div>

          {/* THEMES DROPDOWN */}
          {isThemesOpen && (
            <div className="flex flex-col space-y-6 w-[250px] rounded-lg shadow-sm py-4 px-6 bg-white absolute top-14 right-0">
              <div className="flex items-center space-x-2">
                <LuLayoutDashboard className="text-(--custom-gray)" />
                <span className="text-(--custom-black)">Dashboards</span>
              </div>

              <div className="flex items-center space-x-2">
                <LuLayoutDashboard className="text-(--custom-gray)" />
                <span className="text-(--custom-black)">Dashboards</span>
              </div>

              <div className="flex items-center space-x-2">
                <LuLayoutDashboard className="text-(--custom-gray)" />
                <span className="text-(--custom-black)">Dashboards</span>
              </div>

              <div className="flex items-center space-x-2">
                <LuLayoutDashboard className="text-(--custom-gray)" />
                <span className="text-(--custom-black)">Dashboards</span>
              </div>

              <div className="flex items-center space-x-2">
                <LuLayoutDashboard className="text-(--custom-gray)" />
                <span className="text-(--custom-black)">Dashboards</span>
              </div>

              <div className="flex items-center space-x-2">
                <LuLayoutDashboard className="text-(--custom-gray)" />
                <span className="text-(--custom-black)">Dashboards</span>
              </div>

              <div className="flex items-center space-x-2">
                <LuLayoutDashboard className="text-(--custom-gray)" />
                <span className="text-(--custom-black)">Dashboards</span>
              </div>

              <div className="flex items-center space-x-2">
                <LuLayoutDashboard className="text-(--custom-gray)" />
                <span className="text-(--custom-black)">Dashboards</span>
              </div>

              <div className="flex items-center space-x-2">
                <LuLayoutDashboard className="text-(--custom-gray)" />
                <span className="text-(--custom-black)">Dashboards</span>
              </div>

              <div className="flex items-center space-x-2">
                <LuLayoutDashboard className="text-(--custom-gray)" />
                <span className="text-(--custom-black)">Dashboards</span>
              </div>
            </div>
          )}
        </div>

        <div className="slide-btn border border-(--custom-color) bg-transparent text-(--custom-color) items-center justify-center rounded-xl px-6 py-2 cursor-pointer hover:text-white hidden sm:flex">
          Add property
        </div>

        <CgMenu
          className="size-6 text-(--custom-color) cursor-pointer lg:hidden"
          onClick={() => handleDropdown(setIsOpen)}
        />

        {/* Overlay */}
        {isOpen && (
          <div
            className="fixed inset-0 bg-black opacity-50 z-0"
            onClick={() => setIsOpen(false)}
          />
        )}

        {/* SIDEBAR */}
        <div
          className={`bg-white z-10 absolute left-0 top-0 h-screen w-[400px] flex flex-col justify-between transition-all ease-in-out duration-500 transform border-r-[0.8px] p-4 border-(--custom-border) shadow-xl flex-grow overflow-y-auto ${
            isOpen ? "left-0" : "left-[-100%]"
          }`}
        >
          {/* TOP SIDEBAR */}
          <div className="">
            <div className="flex items-center justify-between">
              <div className="w-[136px] h-[42px]">
                <img src="src/assets/img/logo.png" alt="Site logo" />
              </div>

              <IoMdClose
                className="text-3xl cursor-pointer text-(--custom-black)"
                onClick={() => setIsOpen(false)}
              />
            </div>

            <hr className="text-(--custom-border) my-3" />

            <nav>
              <ul>
                <li className="border-b border-(--custom-border) py-2">
                  <div
                    className="flex items-center justify-between cursor-pointer group"
                    onClick={() => handleDropdown(setIsHomeOpen)}
                  >
                    <NavLink
                      to="/"
                      className={({ isActive }) =>
                        `group-hover:text-(--custom-color) group-hover:transition-all group-hover:duration-100 ${
                          isActive
                            ? "text-(--custom-color)"
                            : "text-(--custom-black)"
                        }`
                      }
                    >
                      Home
                    </NavLink>
                    {isHomeOpen ? <FaAngleUp /> : <FaAngleDown />}
                  </div>

                  <div
                    className={`${
                      isHomeOpen
                        ? "max-h-full opacity-100"
                        : "max-h-0 opacity-0"
                    } overflow-hidden transition-all duration-300 ease-in-out  pl-4 mt-2 flex flex-col space-y-1 border-l-[0.8px] border-l-(--custom-border)`}
                  >
                    <NavLink
                      to="/home1"
                      className={`hover:text-(--custom-color) duration-100 ${
                        isHomeOpen
                          ? "opacity-100 translate-y-0 delay-100"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Home 1
                    </NavLink>
                    <NavLink
                      to="/home2"
                      className={`hover:text-(--custom-color) ${
                        isHomeOpen
                          ? "opacity-100 translate-y-0 delay-200"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Home 2
                    </NavLink>
                    <NavLink
                      to="/home3"
                      className={`hover:text-(--custom-color) ${
                        isHomeOpen
                          ? "opacity-100 translate-y-0 delay-300"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Home 3
                    </NavLink>
                  </div>
                </li>

                <li className="border-b border-(--custom-border) py-2">
                  <div
                    className="flex items-center justify-between cursor-pointer group"
                    onClick={() => handleDropdown(setIsListingOpen)}
                  >
                    <NavLink
                      to="/listing"
                      className={({ isActive }) =>
                        `group-hover:text-(--custom-color) group-hover:transition-all group-hover:duration-100 ${
                          isActive
                            ? "text-(--custom-color)"
                            : "text-(--custom-black)"
                        }`
                      }
                    >
                      Listing
                    </NavLink>
                    {isListingOpen ? <FaAngleUp /> : <FaAngleDown />}
                  </div>

                  <div
                    className={`${
                      isListingOpen
                        ? "max-h-full opacity-100"
                        : "max-h-0 opacity-0"
                    } overflow-hidden transition-all duration-300 ease-in-out  pl-4 mt-2 flex flex-col space-y-1 border-l-[0.8px] border-l-(--custom-border)`}
                  >
                    <NavLink
                      to="/home1"
                      className={`hover:text-(--custom-color) ${
                        isListingOpen
                          ? "opacity-100 translate-y-0 delay-100"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Layout
                    </NavLink>
                    <NavLink
                      to="/home2"
                      className={`hover:text-(--custom-color) ${
                        isListingOpen
                          ? "opacity-100 translate-y-0 delay-200"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Feature
                    </NavLink>
                    <NavLink
                      to="/home3"
                      className={`hover:text-(--custom-color) ${
                        isListingOpen
                          ? "opacity-100 translate-y-0 delay-300"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Listing Details
                    </NavLink>
                  </div>
                </li>

                <li className="border-b border-(--custom-border) py-2">
                  <div
                    className="flex items-center justify-between cursor-pointer group"
                    onClick={() => handleDropdown(setIsPageOpen)}
                  >
                    <NavLink
                      to="/pages"
                      className={({ isActive }) =>
                        `group-hover:text-(--custom-color) group-hover:transition-all group-hover:duration-100 ${
                          isActive
                            ? "text-(--custom-color)"
                            : "text-(--custom-black)"
                        }`
                      }
                    >
                      Pages
                    </NavLink>
                    {isPageOpen ? <FaAngleUp /> : <FaAngleDown />}
                  </div>

                  <div
                    className={`${
                      isPageOpen
                        ? "max-h-full opacity-100"
                        : "max-h-0 opacity-0"
                    } overflow-hidden transition-all duration-300 ease-in-out  pl-4 mt-2 flex flex-col space-y-1 border-l-[0.8px] border-l-(--custom-border)`}
                  >
                    <NavLink
                      to="/agents"
                      className={`hover:text-(--custom-color) ${
                        isPageOpen
                          ? "opacity-100 translate-y-0 delay-100"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Agents
                    </NavLink>
                    <NavLink
                      to="/agencies"
                      className={`hover:text-(--custom-color) ${
                        isPageOpen
                          ? "opacity-100 translate-y-0 delay-200"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Agencies
                    </NavLink>
                    <NavLink
                      to="/career"
                      className={`hover:text-(--custom-color) ${
                        isPageOpen
                          ? "opacity-100 translate-y-0 delay-300"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Career
                    </NavLink>
                    <NavLink
                      to="/faq"
                      className={`hover:text-(--custom-color) ${
                        isPageOpen
                          ? "opacity-100 translate-y-0 delay-400"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Faq's
                    </NavLink>
                    <NavLink
                      to="/dashboard"
                      className={`hover:text-(--custom-color) ${
                        isPageOpen
                          ? "opacity-100 translate-y-0 delay-500"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Dashboard
                    </NavLink>
                  </div>
                </li>

                <li className="border-b border-(--custom-border) py-2">
                  <div
                    className="flex items-center justify-between cursor-pointer group"
                    onClick={() => handleDropdown(setIsBlogOpen)}
                  >
                    <NavLink
                      to="/blog"
                      className={({ isActive }) =>
                        `group-hover:text-(--custom-color) group-hover:transition-all group-hover:duration-100 ${
                          isActive
                            ? "text-(--custom-color)"
                            : "text-(--custom-black)"
                        }`
                      }
                    >
                      Blog
                    </NavLink>
                    {isBlogOpen ? <FaAngleUp /> : <FaAngleDown />}
                  </div>

                  <div
                    className={`${
                      isBlogOpen
                        ? "max-h-full opacity-100"
                        : "max-h-0 opacity-0"
                    } overflow-hidden transition-all duration-300 ease-in-out  pl-4 mt-2 flex flex-col space-y-1 border-l-[0.8px] border-l-(--custom-border)`}
                  >
                    <NavLink
                      to="/home1"
                      className={`hover:text-(--custom-color) ${
                        isBlogOpen
                          ? "opacity-100 translate-y-0 delay-100"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Blog Grid
                    </NavLink>
                    <NavLink
                      to="/home2"
                      className={`hover:text-(--custom-color) ${
                        isBlogOpen
                          ? "opacity-100 translate-y-0 delay-200"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Blog List
                    </NavLink>
                    <NavLink
                      to="/home3"
                      className={`hover:text-(--custom-color) ${
                        isBlogOpen
                          ? "opacity-100 translate-y-0 delay-300"
                          : "opacity-0 translate-y-4"
                      } transition-all duration-300 ease-in-out`}
                    >
                      Blog Details
                    </NavLink>
                  </div>
                </li>

                <li className="border-b border-(--custom-border) py-2">
                  <NavLink
                    to="/contact"
                    className={({ isActive }) =>
                      `flex items-center justify-between ${
                        isActive
                          ? "text-(--custom-color)"
                          : "text-(--custom-black) hover:text-(--custom-color) transition-all duration-100"
                      }`
                    }
                  >
                    Contact
                  </NavLink>
                </li>
              </ul>
            </nav>
          </div>

          {/* BOTTOM SIDEBAR */}
          <div className="flex flex-col space-y-4 py-4">
            <Link
              to={"/contact"}
              className="text-sm font-medium underline hover:text-(--custom-color) transition-all duration-100"
            >
              Need help?
            </Link>

            <div className="flex flex-col space-y-2">
              <p className="text-(--custom-gray) text-sm font-semibold">
                Call Us Now:{" "}
                <span className="text-(--custom-black)">1-555-678-8888</span>
              </p>
              <p className="text-(--custom-gray) text-sm font-semibold">
                Support 24/7:{" "}
                <a
                  href="mailto:themesflat@gmail.com"
                  className="text-(--custom-black)"
                >
                  themesflat@gmail.com
                </a>
              </p>
              <p className="text-(--custom-gray) text-sm font-semibold flex items-center space-x-2">
                <span>Follow us:</span>{" "}
                <div className="flex items-center space-x-2 text-(--custom-black)">
                  <a href="#">
                    <FaFacebookF />
                  </a>
                  <a href="#">
                    <FaXTwitter />
                  </a>
                  <a href="#">
                    <BiLogoLinkedin />
                  </a>
                  <a href="#">
                    <FiInstagram />
                  </a>
                </div>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Navbar;
