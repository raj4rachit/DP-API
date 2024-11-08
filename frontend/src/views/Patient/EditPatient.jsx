import React from 'react';
import { useParams } from 'react-router-dom';

const EditPatient = () => {
    const { id } = useParams();

    return <div className="flex flex-col gap-5">EditPatient : {id}</div>;
};

export default EditPatient;
