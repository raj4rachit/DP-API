import Button from '@components/Button';
import React from 'react';
import { useNavigate } from 'react-router-dom';

const TestSetting2 = () => {
    const navigate = useNavigate();
    return (
        <div>
            TestSetting
            <Button onClick={() => navigate(-1)}>Back</Button>
        </div>
    );
};

export default TestSetting2;
