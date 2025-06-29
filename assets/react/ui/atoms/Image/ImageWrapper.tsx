import React from 'react';
import clsx from 'clsx';
import type { CSSProperties } from 'react';
type ImageSize = 'big' | 'medium' | 'icon';
type ImagePosition = 'center' | 'left' | 'right' | 'custom';

interface ImageWrapperProps {
    src: string;
    alt?: string;
    size?: ImageSize;
    position?: ImagePosition;
    x?: string; // например, '20px'
    y?: string;
    background?: boolean; // если true, то картинка ставится фоном
    className?: string;
}

export const ImageWrapper: React.FC<ImageWrapperProps> = ({
                                                              src,
                                                              alt = '',
                                                              size = 'medium',
                                                              position = 'center',
                                                              x,
                                                              y,
                                                              background = false,
                                                              className,
                                                          }) => {
    const sizeClass = {
        big: 'w-100 h-auto',
        medium: 'w-75 h-auto',
        icon: 'w-25 h-auto',
    }[size];

    const positionClass = {
        center: 'd-flex justify-content-center align-items-center',
        left: 'd-flex justify-content-start align-items-center',
        right: 'd-flex justify-content-end align-items-center',
        custom: '',
    }[position];

    const customStyle: CSSProperties = position === 'custom' ? {
        position: 'absolute',
        left: x || '0px',
        top: y || '0px',
    } : {};


    if (background) {
        return (
            <div
                className={clsx('image-wrapper rounded overflow-hidden position-relative', positionClass, className)}
                style={{
                    backgroundImage: `url(${src})`,
                    backgroundSize: 'cover',
                    backgroundRepeat: 'no-repeat',
                    backgroundPosition: 'center',
                    ...customStyle,
                }}
            />
        );
    }

    return (
        <div className={clsx('image-wrapper position-relative', positionClass, className)} style={customStyle}>
            <img src={src} alt={alt} className={clsx('img-fluid rounded shadow', sizeClass)} />
        </div>
    );
};
