import React, { useEffect } from 'react';
import { NavLink, Outlet, useLocation, useNavigate } from 'react-router-dom';
import CompanyLogo from '@assets/images/svg/Company.svg';
import { useSelector } from 'react-redux';

const AuthLayout = () => {
    const location = useLocation();
    const authData = useSelector((state) => state.auth);
    const navigate = useNavigate();

    useEffect(() => {
        if (authData.isAuthenticated) {
            navigate('/dashboard', { replace: true });
        }
    }, [location, authData]);

    return (
        <div className="h-dvh bg-slate-100 flex items-center justify-center p-3">
            <div className=" bg-white p-5 w-96 rounded-lg">
                {/* <NavLink to={'/dashboard'} className={`sidebar-title justify-center px-2 pt-1 pb-5`}>
                    <img src={CompanyLogo} className="h-10" alt="Company Logo" />
                    <div className={`logo-text truncate relative inline-block`}>
                        <span className="font-semibold text-slate-900">Hospital</span>
                        <small className="block text-xs text-slate-500 font-medium">Management System</small>
                    </div>
                </NavLink> */}

                <Outlet />
            </div>
        </div>
    );
};

export default AuthLayout;
