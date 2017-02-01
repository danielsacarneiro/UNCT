select pessoa.pe_cd, pessoa_vinculo.pe_cd, pessoa_vinculo.vi_cd from pessoa
inner join pessoa_vinculo
on pessoa_vinculo.pe_cd = pessoa.pe_cd


select maxcount(*) from pessoa_vinculo

select * from pessoa
where pe_nome like '%daniel%'

insert into pessoa 
(pe_cd, pe_nome, pe_email) 
values
(68, "daniel sa", "daniel sa");


delete from pessoa
where pe_cd = 68
