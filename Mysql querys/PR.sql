CREATE PROCEDURE `REL_HORARIOS_FUNCIONARIOS`(
    IN EMAIL  VARCHAR(255),
    IN DATA_PONTO VARCHAR(255)
)
BEGIN
    SELECT
        f.email,
        f.nome,
        f.cpf,
        f.funcao,
        f.EMPREGADOR,
        p.hora,
        p.data_ponto
    FROM
        FUNCIONARIOS f
    INNER JOIN TabelaPontos p ON f.email = p.email
    WHERE
        f.email = EMAIL
        AND (DATA_PONTO = '' OR p.data_ponto = DATA_PONTO);
END;
CALL REL_HORARIOS_FUNCIONARIOS ('carlos@email.com','');
