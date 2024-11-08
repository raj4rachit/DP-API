import React from 'react';
import TextFiled from '@components/TextFiled';
import { useForm } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from 'yup';
import Typography from '@components/Typography';
import Select from '@components/Select';

const schema = yup.object().shape({
    first_name: yup.string().required('First name is required.'),
});

const AddPatient = () => {
    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm({
        resolver: yupResolver(schema),
    });

    const onSubmit = (data) => {
        console.log(data);
    };

    return (
        <div className="flex flex-col gap-2">
            {/* Breadcrumb */}
            <div>
                <label className="flex items-center gap-2">
                    <span className="font-semibold">Create Patient</span>
                </label>
                <small className="text-xs font-normal text-gray-500">{`Dashboard > Patients > Create Patient`}</small>
            </div>

            <div className="bg-white w-full p-3 rounded-md">
                <div className="pb-3 pt-2">
                    <Typography className="font-medium">Patient Registration</Typography>
                </div>
                {/* Form */}
                <form onSubmit={handleSubmit(onSubmit)} className="flex flex-col gap-5">
                    <div className="border rounded-md flex flex-col gap-2 py-2">
                        <Typography className="px-2">Patient Details</Typography>
                        <hr />
                        <div className="px-2 grid grid-cols-1 md:grid-cols-6 lg:grid-cols-12 gap-2">
                            <div className="col-span-1 md:col-span-6 lg:col-span-4">
                                <Typography variant="body2">First Name</Typography>
                                <TextFiled
                                    placeholder="First Name"
                                    size="small"
                                    {...register('first_name')}
                                    error={Boolean(errors?.first_name)}
                                    errorText={Boolean(errors.first_name) && errors.first_name.message}
                                />
                            </div>
                            <div className="col-span-1 md:col-span-6 lg:col-span-4">
                                <Typography variant="body2">Last Name</Typography>
                                <TextFiled placeholder="Last Name" size="small" {...register('last_name')} />
                            </div>
                            <div className="col-span-1 md:col-span-6 lg:col-span-4">
                                <Typography variant="body2">Middle Name</Typography>
                                <TextFiled placeholder="Middle Name" size="small" {...register('middle_name')} />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <Typography variant="body2">Gender</Typography>
                                <Select size="small" {...register('gender')} defaultValue="Male">
                                    <option className="text-sm">Male</option>
                                    <option className="text-sm">Female</option>
                                    <option className="text-sm">Other</option>
                                </Select>
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <Typography variant="body2">SSN Number</Typography>
                                <TextFiled size="small" placeholder="SSN Number" {...register('ssn_number')} />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <Typography variant="body2">Date Of Birth</Typography>
                                <TextFiled placeholder="Date Of Birth" size="small" {...register('date_of_birth')} />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <Typography variant="body2">Marital Status</Typography>
                                <TextFiled size="small" placeholder="Marital Status" {...register('marital_status')} />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <Typography variant="body2">Primary Phone Number</Typography>
                                <TextFiled
                                    size="small"
                                    placeholder="Primary Phone Number"
                                    {...register('primary_phone_number')}
                                />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <Typography variant="body2">Secondary Phone Number</Typography>
                                <TextFiled
                                    size="small"
                                    placeholder="Secondary Phone Number"
                                    {...register('secondary_phone_number')}
                                />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <Typography variant="body2">Home Phone Number</Typography>
                                <TextFiled
                                    size="small"
                                    placeholder="Home Phone Number"
                                    {...register('home_phone_number')}
                                />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <Typography variant="body2">Work Phone Number</Typography>
                                <TextFiled
                                    size="small"
                                    placeholder="Work Phone Number"
                                    {...register('work_phone_number')}
                                />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <label className="text-sm truncate">Email Address</label>
                                <TextFiled size="small" placeholder="Email Address" {...register('email_address')} />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <label className="text-sm truncate">Language</label>
                                <TextFiled size="small" placeholder="Patient Email Address" {...register('language')} />
                            </div>
                        </div>
                    </div>

                    {/* Patient Details Medical Info */}
                    <div className="border rounded-md flex flex-col gap-2 py-2">
                        <Typography className="px-2">Patient Medical Info</Typography>
                        <hr />
                        <div className="px-2 grid grid-cols-1 md:grid-cols-6 lg:grid-cols-12 gap-2">
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <Typography variant="body2">Medical Aid & Private</Typography>
                                <Select size="small" {...register('medical_private')} defaultValue="">
                                    <option className="text-sm">Medical Aid Patient</option>
                                    <option className="text-sm">Private Patient</option>
                                </Select>
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <Typography variant="body2">MRN Number</Typography>
                                <TextFiled size="small" placeholder="MRN Number" {...register('mrn_number')} />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <label className="text-sm truncate">Race</label>
                                <TextFiled size="small" placeholder="Race" {...register('race')} />
                            </div>
                            <div className="col-span-1 md:col-span-3 lg:col-span-6">
                                <label className="text-sm truncate">Ethnicity</label>
                                <TextFiled size="small" placeholder="Ethnicity" {...register('ethnicity')} />
                            </div>
                        </div>
                    </div>

                    <div className="inline-flex gap-2 justify-end">
                        <button className="px-3 py-1.5 border border-gray-500 text-gray-600 rounded-md">Back</button>
                        <button
                            type="submit"
                            className="px-3 py-1.5 text-white rounded-md bg-teal-700 focus:bg-teal-900 outline-none"
                        >
                            Save & Next
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default AddPatient;
