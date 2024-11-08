import React from 'react';
import { NavLink } from 'react-router-dom';

const ForgotPassword = () => {
    return (
        <form className="flex flex-col gap-2">
            <input
                type="email"
                className="border p-2 outline-none focus:border focus:border-slate-600 rounded-lg"
                placeholder="Email"
            />

            <button className="p-2 bg-indigo-500 rounded-lg text-white">Submit</button>

            <span className="text-sm text-center">
                Already have account?{' '}
                <NavLink to={'/auth/login'} className="text-indigo-800">
                    Sign In
                </NavLink>
            </span>
        </form>
    );
};

export default ForgotPassword;
