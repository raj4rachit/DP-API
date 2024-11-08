import React, { useState } from 'react';
import useMenu from '@hooks/useMenu';
import ProfilePicture from '@assets/images/ProfilePicture.webp';
import { FiMenu } from 'react-icons/fi';
import { Bell, Settings } from 'lucide-react';
import { Menu, MenuItem } from '@components/Menu';
import { NavLink, useNavigate } from 'react-router-dom';
import Typography from '@components/Typography';
import { useDispatch } from 'react-redux';
import { logout } from '@store/authSlice';
import { useMutation } from '@tanstack/react-query';
import { LogoutAPI } from '@apis/Authentication';

const Header = () => {
    const { isOpen, setIsOpen } = useMenu();
    const navigate = useNavigate();
    const dispatch = useDispatch();

    const [menuOpen, setMenuOpen] = useState(false);

    const handleButtonClick = (event, row) => {
        const buttonRect = event.target.getBoundingClientRect();
        console.log(buttonRect);
        setMenuPosition({ x: buttonRect.left, y: buttonRect.bottom });
        // setSelectedRow(row);
        setMenuOpen(true);
    };

    const closeMenu = () => {
        setMenuOpen(false);
    };

    const { mutate, isPending } = useMutation({
        mutationFn: () => LogoutAPI(),
        onSuccess: (response) => {
            const { data } = response;
            dispatch(logout());
            navigate('/auth/login', { replace: true });
        },
    });

    return (
        <header className="p-1 flex items-center justify-between">
            <div
                className="cursor-pointer content-center p-2"
                onClick={() => {
                    setIsOpen(!isOpen);
                }}
            >
                <FiMenu />
            </div>

            <section className="flex gap-3 items-center py-1 px-2">
                <Settings
                    strokeWidth={1.5}
                    size="20"
                    className="text-slate-900 cursor-pointer"
                    onClick={(event) => handleButtonClick(event)}
                />
                {/* Notificatoin Secton */}
                <Menu trigger={<Bell size={'20'} strokeWidth={1.5} className="text-slate-900 cursor-pointer" />}>
                    <MenuItem>demo</MenuItem>
                </Menu>

                {/* profile Secton */}
                <Menu
                    trigger={
                        <img
                            className="h-8 rounded-full cursor-pointer bg-slate-300"
                            alt=""
                            src={ProfilePicture || ''}
                        />
                    }
                >
                    <MenuItem as={NavLink} to={'/profile'}>
                        <Typography variant="body2">Profile</Typography>
                    </MenuItem>
                    <MenuItem>
                        <Typography variant="body2">Settings</Typography>
                    </MenuItem>
                    <MenuItem className="" onClick={() => mutate()}>
                        <Typography variant="body2" className="text-red-800 font-medium">
                            Logout
                        </Typography>
                    </MenuItem>
                </Menu>
            </section>

            {/*  */}
        </header>
    );
};

export default Header;
