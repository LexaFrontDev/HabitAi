import React from 'react';
import clsx from 'clsx';
import { Link, LinkProps } from 'react-router-dom';

type ButtonVariants =
    | 'primary'
    | 'secondary'
    | 'danger'
    | 'login'
    | 'register'
    | 'big'
    | 'big-orange'
    | 'big-green'
    | 'subscribe';

interface BaseProps {
    children: React.ReactNode;
    variant?: ButtonVariants;
    className?: string;
}

type ButtonAsButton = BaseProps & React.ButtonHTMLAttributes<HTMLButtonElement> & { as?: 'button' };
type ButtonAsLink = BaseProps & LinkProps & { as: 'link' };
type ButtonAsAnchor = BaseProps & React.AnchorHTMLAttributes<HTMLAnchorElement> & { as: 'anchor' };

type ButtonProps = ButtonAsButton | ButtonAsLink | ButtonAsAnchor;

export const Button: React.FC<ButtonProps> = ({
                                                  children,
                                                  className,
                                                  variant = 'primary',
                                                  as = 'button',
                                                  ...props
                                              }) => {
    const base = 'px-4 py-2 rounded-xl font-medium transition-colors duration-200';
    const variants: Record<ButtonVariants, string> = {
        primary: 'bg-blue-600 text-white hover:bg-blue-700',
        secondary: 'bg-gray-100 text-gray-800 hover:bg-gray-200',
        danger: 'bg-red-600 text-white hover:bg-red-700',
        login: 'btn btn-outline-secondary me-2',
        register: 'btn btn-outline-secondary me-2',
        big: 'btn btn-lg btn-primary',
        'big-orange': 'btn btn-lg text-white bg-orange-500 hover:bg-orange-600',
        'big-green': 'btn btn-lg text-white bg-green-500 hover:bg-green-600',
        subscribe: 'btn-subscribe bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-xl fw-semibold',
    };


    const classes = clsx(base, variants[variant], className);

    if (as === 'link') {
        return <Link className={classes} {...(props as LinkProps)}>{children}</Link>;
    }

    if (as === 'anchor') {
        return <a className={classes} {...(props as React.AnchorHTMLAttributes<HTMLAnchorElement>)}>{children}</a>;
    }

    return <button className={classes} {...(props as React.ButtonHTMLAttributes<HTMLButtonElement>)}>{children}</button>;
};
