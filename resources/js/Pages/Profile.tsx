import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import Layout from '../Components/Layout';

interface ProfileProps {
    user?: {
        name: string;
        email: string;
        profile?: string;
    };
    errors?: Record<string, string>;
    success?: string;
}

export default function Profile({ user, errors = {}, success }: ProfileProps) {
    const { data, setData, patch, processing } = useForm({
        name: user?.name || '',
        email: user?.email || '',
        password: '',
        password_confirmation: '',
        profilePicture: null as File | null,
        agree: false,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Ensure we're sending the current form values
        const formData = {
            name: data.name,
            email: data.email,
            password: data.password,
            password_confirmation: data.password_confirmation,
            profilePicture: data.profilePicture,
        };

        patch('/profile', formData, {
            forceFormData: true,
            onSuccess: (page) => {
                console.log('Profile update successful:', page);
            },
            onError: (errors) => {
                console.log('Profile update errors:', errors);
            }
        });
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
                    {success && (
                        <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {success}
                        </div>
                    )}
                    <form onSubmit={handleSubmit}>
                        <div className="mb-4">
                            <label htmlFor="name" className="block text-gray-700 text-sm font-bold mb-2">Name</label>
                            <input
                                type="text"
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                id="name"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                required
                            />
                            {errors.name && (
                                <div className="text-red-500 text-sm mt-1">{errors.name}</div>
                            )}
                        </div>
                        <div className="mb-4">
                            <label htmlFor="exampleInputEmail1" className="block text-gray-700 text-sm font-bold mb-2">Email address</label>
                            <input
                                type="email"
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                id="exampleInputEmail1"
                                aria-describedby="emailHelp"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                required
                            />
                            <small className="text-gray-600 text-xs">We'll never share your email with anyone else.</small>
                            {errors.email && (
                                <div className="text-red-500 text-sm mt-1">{errors.email}</div>
                            )}
                        </div>
                        <div className="mb-4">
                            <label htmlFor="exampleInputPassword1" className="block text-gray-700 text-sm font-bold mb-2">New password</label>
                            <input
                                type="password"
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                id="exampleInputPassword1"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                            />
                            {errors.password && (
                                <div className="text-red-500 text-sm mt-1">{errors.password}</div>
                            )}
                        </div>
                        <div className="mb-4">
                            <label htmlFor="exampleInputPassword2" className="block text-gray-700 text-sm font-bold mb-2">Confirm password</label>
                            <input
                                type="password"
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                id="exampleInputPassword2"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                            />
                        </div>
                        <div className="mb-4">
                            <label className="block text-gray-700 text-sm font-bold mb-2">Profile Picture</label>
                            {user?.profile && (
                                <div className="mb-3">
                                    <img
                                        src={user.profile}
                                        alt="Current profile picture"
                                        className="w-20 h-20 rounded-full object-cover border-2 border-gray-300"
                                    />
                                    <p className="text-sm text-gray-600 mt-1">Current profile picture</p>
                                </div>
                            )}
                            <input
                                type="file"
                                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                id="inputGroupFile03"
                                aria-label="Upload"
                                onChange={handleFileChange}
                                accept="image/*"
                            />
                            <p className="text-sm text-gray-600 mt-1">Allowed: JPEG, PNG, JPG, GIF (max 2MB)</p>
                        </div>
                        <div className="flex items-center mb-4">
                            <input
                                type="checkbox"
                                className="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                id="exampleCheck1"
                                checked={data.agree}
                                onChange={(e) => setData('agree', e.target.checked)}
                            />
                            <label className="ml-2 text-sm text-gray-700" htmlFor="exampleCheck1">
                                Check me out
                            </label>
                        </div>
                        <button
                            type="submit"
                            className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 disabled:opacity-50"
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
