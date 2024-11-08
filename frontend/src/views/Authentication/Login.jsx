import { useForm } from 'react-hook-form';
import { NavLink } from 'react-router-dom';
import TextFiled from '@components/TextFiled';
import * as yup from 'yup';
import { yupResolver } from '@hookform/resolvers/yup';
import { useMutation } from '@tanstack/react-query';

import { LoginAPI } from '@apis/Authentication';
import Button from '@components/Button';
import { useDispatch } from 'react-redux';
import { login } from '@store/authSlice';
import toast from 'react-hot-toast';

const schema = yup.object().shape({
    email: yup.string().email('Please enter valid email address.').required('Email is required.'),
    password: yup.string().required('Password is required.'),
});

const Login = () => {
    const dispatch = useDispatch();
    const {
        register,
        handleSubmit,
        formState: { errors },
        reset,
    } = useForm({
        defaultValues: { email: 'admin@gmail.com', password: '123456789' },
        resolver: yupResolver(schema),
    });

    const { mutate, isPending } = useMutation({
        mutationFn: (data) => LoginAPI(data),
        onSuccess: (response) => {
            reset();
            const { data } = response;
            dispatch(login({ user: data.data, token: data.accessToken }));
            toast.success(data.message);
        },
        onError: (error) => {
            const { response } = error;
            toast.error(response.data.message);
        },
    });

    return (
        <div className="grid grid-cols-1 gap-4">
            <small className="text-center">Sign-in with credentials</small>
            <form className="flex flex-col gap-2" onSubmit={handleSubmit(mutate)}>
                <div className="">
                    <TextFiled
                        type="email"
                        placeholder="Enter Email"
                        {...register('email')}
                        error={Boolean(errors.email)}
                        errorText={Boolean(errors.email) && errors.email.message}
                    />
                </div>
                <div className="">
                    <TextFiled
                        type="password"
                        placeholder="Enter Password"
                        {...register('password')}
                        error={Boolean(errors?.password)}
                        errorText={Boolean(errors.password) && errors.password.message}
                    />
                </div>

                <NavLink to={'/auth/forgot-password'} className="text-sm text-end text-indigo-800">
                    Forgot Password ?
                </NavLink>
                <Button
                    type="submit"
                    className="p-2 bg-teal-700 hover:bg-teal-900 rounded-lg text-white"
                    disabled={isPending}
                >
                    Sign In
                </Button>
            </form>

            <span className="text-sm text-center">
                You don't have account ? <NavLink className="text-indigo-800">Sign Up</NavLink>
            </span>
        </div>
    );
};

export default Login;
