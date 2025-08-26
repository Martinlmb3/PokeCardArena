import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import Layout from '../Components/Layout';

interface ProfileProps {
    user?: {
        name: string;
        email: string;
    };
    errors?: Record<string, string>;
}

export default function Profile({ user, errors = {} }: ProfileProps) {
    const { data, setData, post, processing } = useForm({
        firstName: user?.name || '',
        email: user?.email || '',
        password: '',
        passwordConfirmation: '',
        profilePicture: null as File | null,
        agree: false,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/profile');
    };

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] || null;
        setData('profilePicture', file);
    };

    return (
        <Layout>
            <Head title="My Profile - PokéCard Arena" />
            <main className="bg-gray-200 p-5">
                <section className="flex flex-col my-8 mx-auto max-w-4xl bg-white shadow-xl p-4">
                    <h1 className="text-center text-2xl font-bold mb-4">My Profile</h1>
                    <form onSubmit={handleSubmit}>
                        <div className="mb-3">
                            <label htmlFor="firstName" className="form-label">First Name</label>
                            <input 
                                type="text" 
                                className="form-control" 
                                id="firstName"
                                value={data.firstName}
                                onChange={(e) => setData('firstName', e.target.value)}
                                required
                            />
                            {errors.firstName && (
                                <div className="text-red-500 text-sm mt-1">{errors.firstName}</div>
                            )}
                        </div>
                        <div className="mb-3">
                            <label htmlFor="exampleInputEmail1" className="form-label">Email address</label>
                            <input 
                                type="email" 
                                className="form-control" 
                                id="exampleInputEmail1" 
                                aria-describedby="emailHelp"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                required
                            />
                            <div id="emailHelp" className="form-text">We'll never share your email with anyone else.</div>
                            {errors.email && (
                                <div className="text-red-500 text-sm mt-1">{errors.email}</div>
                            )}
                        </div>
                        <div className="mb-3">
                            <label htmlFor="exampleInputPassword1" className="form-label">New password</label>
                            <input 
                                type="password" 
                                className="form-control" 
                                id="exampleInputPassword1"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                            />
                            {errors.password && (
                                <div className="text-red-500 text-sm mt-1">{errors.password}</div>
                            )}
                        </div>
                        <div className="mb-3">
                            <label htmlFor="exampleInputPassword2" className="form-label">Confirm password</label>
                            <input 
                                type="password" 
                                className="form-control" 
                                id="exampleInputPassword2"
                                value={data.passwordConfirmation}
                                onChange={(e) => setData('passwordConfirmation', e.target.value)}
                            />
                        </div>
                        <div className="input-group mb-3">
                            <input 
                                type="file" 
                                className="form-control" 
                                id="inputGroupFile03" 
                                aria-describedby="inputGroupFileAddon03" 
                                aria-label="Upload"
                                onChange={handleFileChange}
                                accept="image/*"
                            />
                        </div>
                        <div className="mb-3 form-check">
                            <input 
                                type="checkbox" 
                                className="form-check-input" 
                                id="exampleCheck1"
                                checked={data.agree}
                                onChange={(e) => setData('agree', e.target.checked)}
                            />
                            <label className="form-check-label" htmlFor="exampleCheck1">
                                Check me out
                            </label>
                        </div>
                        <button 
                            type="submit" 
                            className="btn btn-primary"
                            disabled={processing}
                        >
                            {processing ? 'Updating...' : 'Submit'}
                        </button>
                    </form>
                </section>
            </main>
        </Layout>
    );
}