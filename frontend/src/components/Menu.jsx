import React, { useEffect, useRef, useState } from 'react';
import { NavLink, Link } from 'react-router-dom';

// Hook to detect clicks outside the menu
function useOutsideClick(ref, callback) {
    useEffect(() => {
        function handleClickOutside(event) {
            if (ref.current && !ref.current.contains(event.target)) {
                callback();
            }
        }
        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [ref, callback]);
}

const Menu = ({ trigger, children }) => {
    const [isOpen, setIsOpen] = useState(false);
    const menuRef = useRef(null);

    useOutsideClick(menuRef, () => setIsOpen(false));

    return (
        <div className="relative inline-block text-left" ref={menuRef}>
            {/* Dropdown Trigger */}
            <div className="cursor-pointer" onClick={() => setIsOpen((prev) => !prev)}>
                {trigger}
            </div>

            {/* Dropdown Menu */}
            {isOpen && (
                <div className="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div className="p-1">
                        {/* Pass setIsOpen to children */}
                        {React.Children.map(children, (child) => {
                            return React.cloneElement(child, {
                                closeMenu: () => setIsOpen(false),
                            });
                        })}
                    </div>
                </div>
            )}
        </div>
    );
};

// Enhanced MenuItem component
const MenuItem = ({ children, onClick, closeMenu, to, as = NavLink, ...props }) => {
    const handleClick = () => {
        if (onClick) onClick();
        if (closeMenu) closeMenu();
    };

    if (to) {
        const Component = as;
        return (
            <Component
                to={to}
                className="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 hover:rounded-md hover:text-gray-900"
                onClick={handleClick}
                {...props}
            >
                {children}
            </Component>
        );
    }

    // Otherwise, render as a button
    return (
        <button
            className="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 hover:rounded-md hover:text-gray-900"
            onClick={handleClick}
        >
            {children}
        </button>
    );
};

export { Menu, MenuItem };
