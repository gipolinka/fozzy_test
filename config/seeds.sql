INSERT INTO
product (id, name, cpu_unit, ram_mb, disk_size_gb)
VALUES (UNHEX(REPLACE(UUID(), '-', '')),'Тариф 512', 1, 512, 20),
       (UNHEX(REPLACE(UUID(), '-', '')),'Тариф 1024', 2, 1024, 40),
       (UNHEX(REPLACE(UUID(), '-', '')),'Тариф 1024/80', 2, 1024, 80),
       (UNHEX(REPLACE(UUID(), '-', '')),'Тариф 2048', 4, 2048, 80),
       (UNHEX(REPLACE(UUID(), '-', '')),'Тариф 8192', 6, 8192, 120);