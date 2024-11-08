import React, { useEffect, useState } from 'react';
import useMenu from '@hooks/useMenu';
import useScreenSize from '@hooks/useScreenSize';
import CompanyLogo from '@assets/images/svg/Company.svg';
// import CompanyLogo from "@assets/images/svg/Company2.svg";

import MenuItems from './MenuItems';
import { NavLink, useLocation } from 'react-router-dom';
import { ChevronDown } from 'lucide-react';
import AppBar from '@components/AppBar';
import SubMenu from './SubMenu';

const Sidebar = () => {
    const location = useLocation();
    const locationName = location.pathname.replace(/^\/+/, '');
    const { isOpen, setIsOpen, toggleMenu } = useMenu();
    const { isMobile } = useScreenSize();

    const [activeSubmenu, setActiveSubmenu] = useState(null);

    const toggleSubmenu = (i) => {
        if (activeSubmenu === i) {
            setActiveSubmenu(null);
        } else {
            setActiveSubmenu(i);
        }
    };

    useEffect(() => {
        const locationArray = locationName.split('/');
        let submenuIndex = null;

        MenuItems.map((menu, i) => {
            if (!menu.child) {
                const menuLinkArray = menu.link.replace(/^\/+/, '').split('/');
                if (menuLinkArray.some((val) => locationArray.includes(val))) {
                    submenuIndex = i;
                }
            } else {
                menu.child.map((cmenu) => {
                    if (!cmenu.child) {
                        const childMenuLinkArray = cmenu.link.replace(/^\/+/, '').split('/');
                        if (childMenuLinkArray.some((val) => locationArray.includes(val))) {
                            submenuIndex = i;
                            locationArray;
                        }
                    }
                });
            }
        });
        setActiveSubmenu(submenuIndex);

        document.title = `Dashcode | ${locationName.replace(/\//g, '')}`;
    }, [location]);

    return (
        <>
            <aside
                className={`sidebar 
                    ${isOpen ? 'w-64' : 'w-0 md:w-[56px] md:hover:w-64'} 
                    ${isMobile ? 'absolute top-0 left-0' : 'relative'}
                `}
            >
                <nav className={`truncate md:whitespace-normal flex-1 flex flex-col`}>
                    {/* head */}
                    {/* <div className="p-2">
                        <div className={`sidebar-title px-2 pt-1`}>
                            <img src={CompanyLogo} className="h-10" alt="Company Logo" />
                            <div className={`logo-text truncate relative inline-block`}>
                                <span className="font-semibold text-slate-900">Hospital</span>
                                <small className="block text-xs text-slate-500 font-medium">Management System</small>
                            </div>
                        </div>
                    </div> */}

                    {/* Menu */}
                    <div className="sidebar-menu overflow-auto p-2">
                        <ul className="flex flex-col gap-1 py-2">
                            {MenuItems.map((menu, i) => (
                                <li key={i}>
                                    {!menu.child ? (
                                        <NavLink
                                            to={menu.link}
                                            className={({ isActive }) =>
                                                `w-full cursor-pointer inline-flex gap-2 items-center rounded-lg hover:bg-cyan-400/20 py-2 ${
                                                    isActive
                                                        ? 'sidebar-parent-active'
                                                        : 'hover:text-cyan-800 hover:font-medium'
                                                }`
                                            }
                                            onClick={() => {
                                                toggleSubmenu(i);
                                                toggleMenu();
                                            }}
                                        >
                                            <div className="pl-2 truncate md:whitespace-normal md:overflow-visible">
                                                <span className="text-xl">{menu.icon}</span>
                                            </div>
                                            <div className="truncate flex-1 flex justify-between items-center pr-2">
                                                <span className="truncate text-sm">{menu.title}</span>
                                            </div>
                                        </NavLink>
                                    ) : (
                                        <>
                                            <div
                                                className={`w-full cursor-pointer inline-flex gap-2 items-center rounded-lg py-2
                          ${
                              activeSubmenu === i
                                  ? 'sidebar-parent-active'
                                  : 'hover:bg-cyan-400/20 hover:text-cyan-800 hover:font-medium'
                          }`}
                                                onClick={() => {
                                                    toggleSubmenu(i);
                                                }}
                                            >
                                                <div className="pl-2 truncate md:whitespace-normal md:overflow-visible">
                                                    <span className="text-xl font-thin">{menu.icon}</span>
                                                </div>
                                                <div className="truncate flex-1 flex justify-between items-center pr-2">
                                                    <span className="truncate text-sm">{menu.title}</span>
                                                    <span
                                                        className={`truncate ${activeSubmenu === i ? 'rotate-90' : ''}`}
                                                    >
                                                        <ChevronDown strokeWidth={1.5} size={'18px'} />
                                                    </span>
                                                </div>
                                            </div>
                                            <div className={`${isOpen ? '' : 'collapse-menu'}`}>
                                                <SubMenu
                                                    activeSubmenu={activeSubmenu}
                                                    item={menu}
                                                    i={i}
                                                    locationName={locationName}
                                                />
                                            </div>
                                        </>
                                    )}
                                </li>
                            ))}
                        </ul>
                    </div>
                </nav>
            </aside>
            <div
                className={`fixed inset-0 bg-black ${
                    isOpen ? 'bg-opacity-10 z-30' : 'bg-opacity-0 z-0'
                } transition-all duration-300 md:hidden`}
                onClick={() => setIsOpen(false)}
            />
        </>
    );
};

export default Sidebar;
