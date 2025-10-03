import React from 'react';
import { Head } from '@inertiajs/react';
import { router } from '@inertiajs/react';
import { useForm } from 'react-hook-form';
import Layout from '../Components/Layout';

interface SignupFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    agree?: boolean;
}

interface SignupProps {
    errors?: Record<string, string>;
}

export default function Signup({ errors }: SignupProps) {
    const { register, handleSubmit, watch, formState: { errors: formErrors, isSubmitting } } = useForm<SignupFormData>();
    const password = watch('password');

    const onSubmit = (data: SignupFormData) => {
        router.post('/signup', data as Record<string, any>, {
            onError: (errors) => {
                console.log('Signup errors:', errors);
            }
        });
    };

    return (
        <Layout>
            <Head title="Sign Up - PokéCard Arena" />
            <main className="bg-gray-200 p-5">
                <section className="bg-white mx-auto max-w-7xl px-8 py-6 w-150 mt-4 sm:px-6 lg:px-8 shadow-xl">
                    <form onSubmit={handleSubmit(onSubmit)}>
                        <div className="mb-4">
                            <label htmlFor="name" className="block text-gray-700 text-sm font-bold mb-2">Name</label>
                            <input 
                                type="text" 
                                className={`w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500 ${formErrors.name || errors?.name ? 'border-red-500' : 'border-gray-300'}`}
                                id="name" 
                                placeholder="Enter your name"
                                {...register('name', { 
                                    required: 'Name is required',
                                    minLength: {
                                        value: 2,
                                        message: 'Name must be at least 2 characters'
                                    }
                                })}
                            />
                            {(formErrors.name || errors?.name) && (
                                <div className="text-red-500 text-sm mt-1">
                                    {formErrors.name?.message || errors?.name}
                                </div>
                            )}
                        </div>
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
                                    required: 'Password is required',
                                    minLength: {
                                        value: 6,
                                        message: 'Password must be at least 6 characters'
                                    }
                                })}
                            />
                            {(formErrors.password || errors?.password) && (
                                <div className="text-red-500 text-sm mt-1">
                                    {formErrors.password?.message || errors?.password}
                                </div>
                            )}
                        </div>
                        <div className="mb-4">
                            <label htmlFor="password_confirmation" className="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                            <input 
                                type="password" 
                                className={`w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500 ${formErrors.password_confirmation ? 'border-red-500' : 'border-gray-300'}`}
                                id="password_confirmation" 
                                placeholder="Confirm Password"
                                {...register('password_confirmation', { 
                                    required: 'Please confirm your password',
                                    validate: value => value === password || 'Passwords do not match'
                                })}
                            />
                            {formErrors.password_confirmation && (
                                <div className="text-red-500 text-sm mt-1">
                                    {formErrors.password_confirmation.message}
                                </div>
                            )}
                        </div>
                        <div className="flex items-center mb-4">
                            <input 
                                type="checkbox" 
                                className="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                                id="agree"
                                {...register('agree')}
                            />
                            <label className="ml-2 text-sm text-gray-700" htmlFor="agree">
                                I agree to the terms and conditions
                            </label>
                        </div>
                        <button
                            type="submit"
                            className="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:transform-none"
                            disabled={isSubmitting}
                        >
                            {isSubmitting ? 'Creating Account...' : 'Create Account'}
                        </button>
                    </form>
                </section>
            </main>
        </Layout>
    );
}