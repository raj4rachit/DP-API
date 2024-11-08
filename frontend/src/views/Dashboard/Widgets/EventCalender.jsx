import React, { useState } from 'react';
import { addDays, eachDayOfInterval, format, subDays } from 'date-fns';
import { CalendarDays, ChevronLeftIcon, ChevronRightIcon } from 'lucide-react';

import Avatar1 from '@assets/images/avatars/avatar1.jpg';
import { FcClock } from 'react-icons/fc';
import { LuStethoscope } from 'react-icons/lu';

const EventCalender = () => {
    const today = new Date();

    const getDateRange = (date) => {
        const startDate = subDays(date, 2);
        const endDate = addDays(date, 2);
        return eachDayOfInterval({
            start: startDate,
            end: endDate,
        });
    };

    const [selectedDate, setSelectedDate] = useState(today);
    const [dateRange, setDateRange] = useState(getDateRange(today));

    // Update the date range when the selected date changes
    const handleDateChange = (date) => {
        setSelectedDate(date);
        setDateRange(getDateRange(date));
    };

    return (
        <div className="flex flex-col gap-4 h-full overflow-auto">
            <div className="flex justify-between">
                <h2 className="font-semibold text-gray-900">Patient Appointments</h2>
            </div>
            <div className="flex justify-between">
                <h2 className="font-medium text-sm text-gray-900">{format(selectedDate, 'dd MMMM, yyyy')}</h2>
                <button
                    type="button"
                    className="-my-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500"
                >
                    <CalendarDays className="w-5 h-5" aria-hidden="true" />
                </button>
            </div>

            {/* Date Range */}
            <div className="grid grid-cols-7 gap-1">
                <button
                    type="button"
                    onClick={() => handleDateChange(subDays(selectedDate, 1))}
                    className="flex items-center justify-center text-gray-400 hover:text-gray-500"
                >
                    <ChevronLeftIcon className="w-5 h-5" aria-hidden="true" />
                </button>
                {dateRange.map((day) => (
                    //   <div
                    //     key={day}
                    //     className={`p-2 line-clamp-2 rounded-lg cursor-pointer text-center text-sm ${
                    //       day.toDateString() === selectedDate.toDateString()
                    //         ? "bg-indigo-200"
                    //         : "bg-white"
                    //     }`}
                    //     onClick={() => handleDateChange(day)}
                    //   >
                    //     <span className="justify-self-center">{format(day, "d MMM")}</span>
                    //   </div>
                    <div
                        key={day}
                        className={`flex items-center justify-center px-3 py-3 border rounded-lg cursor-pointer text-center text-sm transition-transform duration-300 ease-in-out
          ${
              day.toDateString() === selectedDate.toDateString()
                  ? 'bg-indigo-200 text-indigo-800 scale-105'
                  : 'bg-white hover:bg-gray-100'
          }`}
                        onClick={() => handleDateChange(day)}
                    >
                        <span className="font-medium">{format(day, 'd MMM')}</span>
                    </div>
                ))}
                <button
                    onClick={() => handleDateChange(addDays(selectedDate, 1))}
                    type="button"
                    className="flex items-center justify-center text-gray-400 hover:text-gray-500"
                >
                    <ChevronRightIcon className="w-5 h-5" aria-hidden="true" />
                </button>
            </div>

            {/* Events */}
            <div className="flex-1 flex flex-col gap-2 overflow-auto p-2 max-h-[230px]">
                <div className="p-2 bg-gray-100 rounded-lg">
                    <div className={`flex gap-2`}>
                        <img src={Avatar1} className="h-10 rounded-full" alt="Company Logo" />
                        <div className={`logo-text truncate relative inline-block`}>
                            <span className="text-sm font-semibold text-slate-900">Hospital</span>
                            <small className="flex gap-1 items-center text-xs text-gray-600 truncate">
                                <FcClock className="tex-xs" /> <span>10:00 AM to 12:30 PM</span>
                            </small>
                        </div>
                    </div>
                </div>
                <div className="p-2 bg-gray-100 rounded-lg">
                    <div className={`flex gap-2`}>
                        <img src={Avatar1} className="h-10 rounded-full" alt="Company Logo" />
                        <div className={`logo-text truncate relative inline-block`}>
                            <span className="text-sm font-semibold text-slate-900">Hospital</span>
                            <small className="flex gap-1 items-center text-xs text-gray-600 truncate">
                                <FcClock className="tex-xs" /> <span>10:00 AM to 12:30 PM</span>
                            </small>
                        </div>
                    </div>
                </div>
                <div className="p-2 bg-gray-100 rounded-lg">
                    <div className={`flex gap-2`}>
                        <img src={Avatar1} className="h-10 rounded-full" alt="Company Logo" />
                        <div className={`logo-text truncate relative inline-block`}>
                            <span className="text-sm font-semibold text-slate-900">Hospital</span>
                            <small className="flex gap-1 items-center text-xs text-gray-600 truncate">
                                <FcClock className="tex-xs" /> <span>10:00 AM to 12:30 PM</span>
                            </small>
                        </div>
                    </div>
                </div>
                <div className="p-2 bg-gray-100 rounded-lg">
                    <div className={`flex gap-2`}>
                        <img src={Avatar1} className="h-10 rounded-full" alt="Company Logo" />
                        <div className={`logo-text truncate relative inline-block`}>
                            <span className="text-sm font-semibold text-slate-900">Hospital</span>
                            <small className="flex gap-1 items-center text-xs text-gray-600 truncate">
                                <FcClock className="tex-xs" /> <span>10:00 AM to 12:30 PM</span>
                            </small>
                        </div>
                    </div>
                </div>
                <div className="p-2 bg-gray-100 rounded-lg">
                    <div className={`flex gap-2`}>
                        <img src={Avatar1} className="h-10 rounded-full" alt="Company Logo" />
                        <div className={`logo-text truncate relative inline-block`}>
                            <span className="text-sm font-semibold text-slate-900">Hospital</span>
                            <small className="flex gap-1 items-center text-xs text-gray-600 truncate">
                                <FcClock className="tex-xs" /> <span>10:00 AM to 12:30 PM</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default EventCalender;
