import React from 'react';
import Avatar1 from '@assets/images/avatars/avatar1.jpg';

import Slider from 'react-slick';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

const PatientReview = () => {
    const settings = {
        dots: false,
        infinite: true,
        speed: 1000,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        arrows: false,
    };

    return (
        <div className="flex flex-col gap-2">
            <p className="text-base font-medium p-2">Patient Review's</p>
            <Slider {...settings} className="">
                <ReviewCard />
                <ReviewCard />
            </Slider>
        </div>
    );
};

export default PatientReview;

const ReviewCard = () => {
    return (
        <article className="p-1">
            <div className="p-2 flex flex-col gap-2 rounded-lg bg-gray-100">
                {/* Profile Picture and Name and Rating */}
                <div className="flex gap-2 items-center">
                    <img src={Avatar1} className="h-10 rounded-full" alt="profile picture" />
                    <label className="block mb-2">
                        <span className="text-sm font-semibold">John Doe</span>
                        <small className="block text-xs text-gray-600">Rating: 4.5/5</small>
                    </label>
                </div>
                {/* Feedback */}
                <p className="text-gray-800 text-sm line-clamp-3">
                    The staff was very friendly and the service was prompt. The doctor was attentive and answered all my
                    questions thoroughly. However, I had to wait a bit longer than expected for my appointment.
                </p>
            </div>
        </article>
    );
};
