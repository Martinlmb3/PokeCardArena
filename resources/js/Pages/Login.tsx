import React from 'react';
import { Head } from '@inertiajs/react';
import { router } from '@inertiajs/react';
import { useForm } from 'react-hook-form';
import Layout from '../Components/Layout';

interface LoginFormData {
    email: string;
    password: string;
    remember?: boolean;
}

interface LoginProps {
    errors?: Record<string, string>;
}

export default function Login({ errors }: LoginProps) {
    const { register, handleSubmit, formState: { errors: formErrors, isSubmitting } } = useForm<LoginFormData>();

    const onSubmit = (data: LoginFormData) => {
        router.post('/login', data as Record<string, any>, {
            onError: (errors) => {
                console.log('Login errors:', errors);
            }
        });
    };

    return (
        <Layout>
            <Head title="Login - PokéCard Arena" />
            <main className="bg-gray-200 p-5">
                <div className="bg-white mx-auto max-w-7xl px-8 py-6 w-150 mt-4 sm:px-6 lg:px-8 shadow-xl">
                    <form onSubmit={handleSubmit(onSubmit)}>
                        <div className="mb-4">
                            <label htmlFor="email" className="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input 
                                type="email" 
                                className={`w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500 ${formErrors.email || errors?.email ? 'border-red-500' : 'border-gray-300'}`}
                                id="email" 
                                placeholder="Enter email" 
                                {...register('email', { 
                                    required: 'Email is required',
                                    pattern: {
                                        value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                                        message: 'Invalid email address'
                                    }
                                })}
                            />
                            <small className="text-gray-600 text-xs">
                                We'll never share your email with anyone else.
                            </small>
                            {(formErrors.email || errors?.email) && (
                                <div className="text-red-500 text-sm mt-1">
                                    {formErrors.email?.message || errors?.email}
                                </div>
                            )}
                        </div>
                        <div className="mb-4">
                            <label htmlFor="password" className="block text-gray-700 text-sm font-bold mb-2">Password</label>
                            <input 
                                type="password" 
                                className={`w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500 ${formErrors.password || errors?.password ? 'border-red-500' : 'border-gray-300'}`}
                                id="password" 
                                placeholder="Password" 
                                {...register('password', { 
                                    required: 'Password is required'
                                })}
                            />
                            {(formErrors.password || errors?.password) && (
                                <div className="text-red-500 text-sm mt-1">
                                    {formErrors.password?.message || errors?.password}
                                </div>
                            )}
                        </div>
                        <div className="flex items-center mb-4">
                            <input 
                                type="checkbox" 
                                className="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                                id="remember"
                                {...register('remember')}
                            />
                            <label className="ml-2 text-sm text-gray-700" htmlFor="remember">
                                Remember me
                            </label>
                        </div>
                        <button
                            type="submit"
                            className="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:transform-none"
                            disabled={isSubmitting}
                        >
                            {isSubmitting ? 'Submitting...' : 'Login'}
                        </button>
                    </form>
                </div>
            </main>
        </Layout>
    );
}