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
                        <div className="form-group">
                            <label htmlFor="email">Email</label>
                            <input 
                                type="email" 
                                className={`form-control ${formErrors.email || errors?.email ? 'is-invalid' : ''}`}
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
                            <small id="emailHelp" className="form-text text-muted">
                                We'll never share your email with anyone else.
                            </small>
                            {(formErrors.email || errors?.email) && (
                                <div className="text-red-500 text-sm">
                                    {formErrors.email?.message || errors?.email}
                                </div>
                            )}
                        </div>
                        <div className="form-group">
                            <label htmlFor="password">Password</label>
                            <input 
                                type="password" 
                                className={`form-control ${formErrors.password || errors?.password ? 'is-invalid' : ''}`}
                                id="password" 
                                placeholder="Password" 
                                {...register('password', { 
                                    required: 'Password is required'
                                })}
                            />
                            {(formErrors.password || errors?.password) && (
                                <div className="text-red-500 text-sm">
                                    {formErrors.password?.message || errors?.password}
                                </div>
                            )}
                        </div>
                        <div className="form-check">
                            <input 
                                type="checkbox" 
                                className="form-check-input" 
                                id="remember"
                                {...register('remember')}
                            />
                            <label className="form-check-label" htmlFor="remember">
                                Remember me
                            </label>
                        </div>
                        <button 
                            type="submit" 
                            className="btn btn-primary mt-3"
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