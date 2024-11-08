import React from 'react';
import { FaUser, FaUserDoctor } from 'react-icons/fa6';
import { FcCalendar, FcMoneyTransfer } from 'react-icons/fc';
import BarChart from './ChartData/BarChart';
import PatientReview from './Widgets/PatientReview';
import EventCalender from './Widgets/EventCalender';
import { useSelector } from 'react-redux';

const Dashboard = () => {
    const LoginUser = useSelector((state) => state.auth.user);

    return (
        <div className="h-full">
            <div className="grid grid-cols-1 items-center gap-4">
                <div>
                    <label className="flex items-center gap-2">
                        <span className="font-semibold">Welcome,</span>
                        <span className="font-normal">{LoginUser?.name}</span>
                    </label>
                    <small className="text-xs font-normal text-gray-500">
                        There is the latest update for the last 7 days.
                    </small>
                </div>
            </div>

            <div className="grid grid-cols-2 lg:grid-cols-4 gap-2 pt-3">
                {/* Total Patients */}
                <div className="p-3 rounded-xl bg-indigo-200/40">
                    <div className="flex gap-2 items-center ">
                        <div className="p-2 bg-white rounded-lg">
                            <FaUser className="text-sm font-thin" />
                        </div>
                        <div className="text-base font-normal text-gray-800 truncate">Patient's</div>
                    </div>
                    <div className="text-base font-bold text-gray-600 pt-2">123,54</div>
                </div>

                {/* Total Doctors */}
                <div className="p-3 rounded-xl bg-amber-200/40">
                    <div className="flex gap-2 items-center ">
                        <div className="p-2 bg-white rounded-lg">
                            <FaUserDoctor className="text-sm font-thin" />
                        </div>
                        <div className="text-base font-normal text-gray-800 truncate">Doctor's</div>
                    </div>
                    <div className="text-base font-bold text-gray-600 pt-2">123,54</div>
                </div>

                {/* Earnings */}
                <div className="p-3 rounded-xl bg-lime-200/40">
                    <div className="flex gap-2 items-center ">
                        <div className="p-2 bg-white rounded-lg">
                            <FcMoneyTransfer className="text-sm font-thin" />
                        </div>
                        <div className="text-base font-normal text-gray-800 truncate">Earning's</div>
                    </div>
                    <div className="text-base font-bold text-gray-600 pt-2">$ 12,354</div>
                </div>

                {/* Additional Widget */}
                <div className="p-3 rounded-xl bg-teal-200/40">
                    <div className="flex gap-2 items-center ">
                        <div className="p-2 bg-white rounded-lg">
                            <FcCalendar className="text-sm font-thin" />
                        </div>
                        <div className="text-base font-normal text-gray-800 truncate">Appointment's</div>
                    </div>
                    <div className="text-base font-bold text-gray-600 pt-2">12,354</div>
                </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-5 gap-2 pt-2 ">
                <div className="col-span-1 sm:col-span-5 lg:col-span-3 p-2 bg-white rounded-lg">
                    <p className="p-2">Total Revenue Chart</p>
                    <BarChart />
                </div>
                <div className="col-span-1 sm:col-span-5 lg:col-span-2 p-4 bg-white rounded-lg">
                    <EventCalender />
                </div>
            </div>

            {/* For Future */}
            {/* <div className="grid grid-cols-1 sm:grid-cols-5 gap-2 pt-2">
        <div className="col-span-1 sm:col-span-2 p-2 bg-white rounded-lg">
          <PatientReview />
        </div>
      </div> */}
        </div>
    );
};

export default Dashboard;
