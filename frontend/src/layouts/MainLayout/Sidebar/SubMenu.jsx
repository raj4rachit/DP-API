import useMenu from '@hooks/useMenu';
import { ChevronDown } from 'lucide-react';
import React, { useEffect, useState } from 'react';
import { Collapse } from 'react-collapse';
import { NavLink } from 'react-router-dom';

const SubMenu = ({ activeSubmenu, item, i, locationName }) => {
    const { toggleMenu } = useMenu();

    const [activeMultiMenu, setMultiMenu] = useState(null);

    const toggleMultiMenu = (j) => {
        if (activeMultiMenu === j) {
            setMultiMenu(null);
        } else {
            setMultiMenu(j);
        }
    };

    const hasActiveChild = (children) => {
        return children?.some((child) => child.link === locationName || (child.child && hasActiveChild(child.child)));
    };

    useEffect(() => {
        const findActiveSubmenu = (menuItems, locationName) => {
            let submenuIndex = null;

            menuItems.child.forEach((menu, i) => {
                if (menu.link === locationName) {
                    submenuIndex = i;
                } else if (menu.child) {
                    const ciIndex = menu.child.findIndex((ci) => ci.link === locationName);
                    if (ciIndex !== -1) {
                        submenuIndex = i;
                    } else {
                        menu.child.forEach((subMenu, j) => {
                            if (subMenu.link === locationName || subMenu.child) {
                                submenuIndex = i; // set the parent index
                            }
                        });
                    }
                }
            });
            return submenuIndex;
        };

        const activeIndex = findActiveSubmenu(item, locationName);
        setMultiMenu(activeIndex);
    }, [location]);

    return (
        <Collapse isOpened={activeSubmenu === i}>
            <ul className="p-1 space-y-4">
                {item.child?.map((menu, j) => (
                    <li key={j} className="block pl-4 first:pt-2">
                        {!menu.child ? (
                            <NavLink
                                to={menu.link}
                                onClick={() => {
                                    toggleMultiMenu(j);
                                    toggleMenu();
                                }}
                                className={({ isActive }) =>
                                    `w-full cursor-pointer inline-flex gap-2 items-center rounded-lg ${
                                        isActive ? 'text-cyan-800 font-semibold' : 'hover:text-cyan-800 font-normal'
                                    }`
                                }
                            >
                                {menu.icon && (
                                    <div className="pl-2 truncate md:whitespace-normal md:overflow-visible">
                                        <span className="text-xl font-thin">{menu.icon}</span>
                                    </div>
                                )}
                                <div className="truncate flex-1 flex justify-between items-center pr-2">
                                    <span className="truncate text-sm">{menu.title}</span>
                                </div>
                            </NavLink>
                        ) : (
                            <>
                                <div
                                    className={`w-full cursor-pointer inline-flex gap-2 items-center rounded-lg hover:text-cyan-800 hover:font-medium`}
                                    onClick={() => toggleMultiMenu(j)}
                                >
                                    {menu.icon && (
                                        <div className="truncate md:whitespace-normal md:overflow-visible">
                                            <span className="text-xl font-thin">{menu.icon}</span>
                                        </div>
                                    )}
                                    <div className="truncate flex-1 flex justify-between items-center">
                                        <span className="truncate text-sm font-medium">{menu.title}</span>
                                        <span className={`truncate ${activeMultiMenu === j ? 'rotate-90' : ''}`}>
                                            <ChevronDown strokeWidth={1.5} size={'18px'} />
                                        </span>
                                    </div>
                                </div>
                                <SubMenu
                                    activeSubmenu={activeMultiMenu}
                                    item={menu}
                                    i={j}
                                    locationName={locationName}
                                />
                            </>
                        )}
                    </li>
                ))}
            </ul>
        </Collapse>
    );
};

export default SubMenu;
