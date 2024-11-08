import React, { useEffect } from 'react';
import { MenuProvider } from '@contexts/MenuContext';
import { Outlet, useLocation, useNavigate } from 'react-router-dom';
import Header from './Header';
import Sidebar from './Sidebar';
import { useSelector } from 'react-redux';

const MainLayout = () => {
    const location = useLocation();
    const authData = useSelector((state) => state.auth);
    const navigate = useNavigate();

    useEffect(() => {
        if (!authData.isAuthenticated) {
            navigate('/auth/login', { replace: true });
        }
    }, [location, authData]);

    if (!authData.isAuthenticated) return null;

    return (
        <div className="relative h-dvh bg-slate-100 flex">
            <MenuProvider>
                <Sidebar />
                <main className="flex-1 flex flex-col z-10 overflow-hidden">
                    <Header />
                    <div className="flex-1 flex overflow-auto">
                        <div className="flex-1 p-2">
                            <Outlet />
                        </div>
                    </div>
                </main>
            </MenuProvider>
        </div>
    );
};

export default MainLayout;
