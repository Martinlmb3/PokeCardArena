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
                        <div className="form-group">
                            <label htmlFor="name">Name</label>
                            <input 
                                type="text" 
                                className={`form-control ${formErrors.name || errors?.name ? 'is-invalid' : ''}`}
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
                                <div className="text-red-500 text-sm">
                                    {formErrors.name?.message || errors?.name}
                                </div>
                            )}
                        </div>
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
                                    required: 'Password is required',
                                    minLength: {
                                        value: 6,
                                        message: 'Password must be at least 6 characters'
                                    }
                                })}
                            />
                            {(formErrors.password || errors?.password) && (
                                <div className="text-red-500 text-sm">
                                    {formErrors.password?.message || errors?.password}
                                </div>
                            )}
                        </div>
                        <div className="form-group">
                            <label htmlFor="password_confirmation">Confirm Password</label>
                            <input 
                                type="password" 
                                className={`form-control ${formErrors.password_confirmation ? 'is-invalid' : ''}`}
                                id="password_confirmation" 
                                placeholder="Confirm Password"
                                {...register('password_confirmation', { 
                                    required: 'Please confirm your password',
                                    validate: value => value === password || 'Passwords do not match'
                                })}
                            />
                            {formErrors.password_confirmation && (
                                <div className="text-red-500 text-sm">
                                    {formErrors.password_confirmation.message}
                                </div>
                            )}
                        </div>
                        <div className="form-check">
                            <input 
                                type="checkbox" 
                                className="form-check-input" 
                                id="agree"
                                {...register('agree')}
                            />
                            <label className="form-check-label" htmlFor="agree">
                                I agree to the terms and conditions
                            </label>
                        </div>
                        <button 
                            type="submit" 
                            className="btn btn-primary mt-3"
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