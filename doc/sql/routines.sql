/* Functions */

drop function if exists `error_handler`;
delimiter ;;
create function `error_handler`(
  p_sql_state int,
  p_language_id int
)
  returns json
  begin

    declare v_error_text text;

    set v_error_text = (select get_application_message(p_sql_state, p_language_id));


    insert into error_log (
      text
    ) values (
      v_error_text
    );

    return json_object('success', 0, 'message', v_error_text);

  end ;;
delimiter ;


drop function if exists `get_application_message`;
delimiter ;;
create function `get_application_message`(
  p_sql_state int,
  p_language_id int
)
  returns text charset utf8 collate utf8_unicode_ci
begin

  declare CONST_DEFAULT_LANGUAGE_ID int default 1;

  declare v_language_id int;
  declare v_sql_state int;
  declare v_text text;

  set v_language_id = (
    select id
    from language
    where language.id = p_language_id
  );

  select
    application_message.sql_state,
    application_message.text

  into v_sql_state, v_text

  from application_message

  where
    application_message.active = 1
    and application_message.language_id = if(v_language_id, v_language_id, CONST_DEFAULT_LANGUAGE_ID)
    and application_message.sql_state = p_sql_state

  order by application_message.sql_state;


  if (length(v_text) > 0) then
    return v_text;
  else
    return 'Text has not found';
  end if;

end ;;
delimiter ;

drop function if exists `test_function`;
delimiter ;;
create function `test_function`(
  p_string        varchar(50),
  p_integer       int,
  p_language_name varchar(45)
)
  returns json
  begin

    if (p_string is null || p_integer is null || p_language_name is null) then
      return (select error_handler(-2, p_language_name));
    end if;

    return json_object('success', 1, 'data', json_array(p_string, p_integer, p_language_name));

  end ;;
delimiter ;


/* Procedures */

drop procedure if exists `test_procedure`;
delimiter ;;
create procedure `test_procedure`(
  in p_key           varchar(100),
  in p_name          varchar(100),
  in p_type          int,
  in p_is_exeption   bool, -- need for transaction type
  in p_language_name varchar(45)
)
    return_procedure: begin

    declare const_type_json int default 1;
    declare const_type_recordset int default 2;

    declare v_test_table_id int;

    declare v_result_funcion_error_handler json;

    if (p_key is null || p_name is null || p_language_name is null) && (p_type <> const_type_recordset) then
      set v_result_funcion_error_handler = (select error_handler(-2, p_language_name));

      select v_result_funcion_error_handler as json
      from dual;
      leave return_procedure;
    end if;

    if (p_type = const_type_json) then
      select json_object('success', 1, 'data', json_array(p_key, p_name, p_language_name)) as json
      from dual;

      leave return_procedure;

    elseif (p_type = const_type_recordset) then

      select
        id,
        `key`,
        name
      from test_table;

      leave return_procedure;
    else

      set v_test_table_id = (select id
                             from test_table
                             where test_table.key = p_key);

      if v_test_table_id then
        set v_result_funcion_error_handler = (select error_handler(-9, p_language_name));

        select v_result_funcion_error_handler as json
        from dual;
        leave return_procedure;
      else
        insert into test_table (`key`, name) values (p_key, p_name);

        set v_test_table_id = (select id
                               from test_table
                               where test_table.key = p_key);

        if p_is_exeption then
          select json_object('success', 0, 'message', 'Error', 'id', v_test_table_id) as json from dual;
        else
          select json_object('success', 1, 'data', json_object('id', v_test_table_id)) as json from dual;
        end if;


      end if;


    end if;

  end ;;
delimiter ;