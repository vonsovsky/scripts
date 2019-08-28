process_file( Soubor ) :-
    seeing( StarySoubor ),      % zjištění aktivního proudu
    see( Soubor ),              % otevření souboru Soubor
    repeat,
        read( Term ),           % čtení termu Term
        assert(Term),
        Term == end_of_file,    % je konec souboru?
    !,
    seen,                       % uzavření souboru
    see( StarySoubor ).         % aktivace původního proudu

repeat.                         % vestavěný predikát
repeat :- repeat.

% standardní append, spojení dvou řetězců
append([], S, S).
append([X|XS], S1, [X|S]) :- append(XS, S1, S).

% standardní reverse, obrátí řetězec
reverse([],[]).
reverse([X|R], S) :- reverse(R, Zbytek), append(Zbytek, [X], S).

% poslední znak řetězce
posledniZnak(X, S) :- reverse(X, [S|_]).

% zdroj http://objectmix.com/prolog/781403-how-replace-element-list.html
% replaceal("12", "34", "this12is12a12string", X0), atom_codes(XA,X0).
replaceal(_, _, [], []) :- !.
replaceal(X, Y, Lx, Lyj) :- matchR(X, Lx, LxN), !, replaceal(X, Y, LxN, Lyi), !, append(Y, Lyi, Lyj).
replaceal(X, Y, [H|Lx], [H|Ly]) :- !, replaceal(X, Y, Lx, Ly).

% Match and Replace...
matchR([], X, X) :- !.
matchR([H|L1], [H|L2], X) :- !, matchR(L1, L2, X).

% převede text na malá písmenka
toLower([], []).
toLower([X|String], [Xc|S]) :- convertToLower(X, Xc), toLower(String, S).

% přidá na konec mezeru
mezeraNaKonec(M, S) :- append(M, " ", S).

% zpracuje poslední dvě číslice daného čísla
zpracujDesitkyJednotky(D, Ds) :- D < 20, !, cisla(D, Ds).
zpracujDesitkyJednotky(D, Ds) :- D < 70, !, D1 is D - D // 10 * 10, D2 is D - D1,
                                 cisla(D2, D3), cisla(D1, D4), name(D3, D5), name(D4, D6),
                                 (D1 =:= 1, append(D5, " et ", D7), append(D7, D6, Dn);
                                  D1 =\= 0, append(D5, [32|D6], Dn);                                  
                                  append(D5, D6, Dn)), !, name(Ds, Dn).
zpracujDesitkyJednotky(D, Ds) :- D < 80, !, D1 is D - 60, cisla(D1, D2), name(D2, D3),
                                 append("soixante ", D3, Dn), name(Ds, Dn).
zpracujDesitkyJednotky(D, Ds) :- D1 is D - 80, cisla(D1, D2), name(D2, D3),
                                 (D1 =\= 0, append("quatre-vingt ", D3, Dn);
                                 append("quatre-vingts", D3, Dn)), !, name(Ds, Dn).

% rozdělí čísla na trojice a zpracuje
tisice(S, B) :- S // 100 > 0, !,
                Stovky is S // 100, DesitkyJednotky is S - S // 100 * 100,
                zpracujDesitkyJednotky(DesitkyJednotky, D), name(D, DJ),
                cisla(Stovky, S1), name(S1, S2),
                (Stovky =\= 1, DesitkyJednotky =:= 0, append(S2, " cents ", S3);
                                                      append(S2, " cent ", S3)),
                !, append(S3, DJ, B).
tisice(S, B) :- DesitkyJednotky is S - S // 100 * 100,
                zpracujDesitkyJednotky(DesitkyJednotky, D), name(D, B).

% zpracuje jedno číslo
cislo("0", "zero").
cislo(S, B) :- name(S1, S), cisloA(S1, 0, B).
cisloA(S, T, B) :- S // 1000 > 0, Tisice is S - S // 1000 * 1000,
                   T1 is T + 1, S1 is S // 1000, cisloA(S1, T1, B1),
                   (T1 =:= 1, append(B1, " mille ", B2); T1 =:= 2, append(B1, " million ", B2); append(B1, " milliard ", B2)),
                   !, tisice(Tisice, B3), append(B2, B3, B).
cisloA(S, _, B) :- tisice(S, B).

% načte celé jedno číslo až do nečíselného symbolu 
parsujCislo([], []).
parsujCislo([X|XS], [X|S]) :- (X >= "0", X =< "9"; X =:= " "),
                              !, parsujCislo(XS, S).
parsujCislo(_, []).

% vytáhne čísla z řetězce znaků, odstraní mezery a přepíše na text
parsujNaCisla([], []).
parsujNaCisla([X|XS], S) :- X >= "0", X =< "9",
                     parsujCislo([X|XS], S1),
                     !,
                     replaceal(" ", "", S1, T),
                     cislo(T, TR),
                     posledniZnak(S1, P),
                     (P =:= 32, append(TR, " ", TS), replaceal(S1, TS, [X|XS], S3);     % čísla se vytahují i s mezerami,
                                                     replaceal(S1, TR, [X|XS], S3)),    % které se odstraňují, musíme je vracet
                     !, parsujNaCisla(S3, S).
parsujNaCisla([X|XS], [X|S]) :- parsujNaCisla(XS, S).

% zpracuje fakta change(X, Y) nyní uložené v poli
zpracujFakta([X, Y], Word, S) :- !, replaceal(X, Y, Word, S).

zpracujList([], S, S).
zpracujList([X|XS], Word, T) :- zpracujFakta(X, Word, S), zpracujList(XS, S, T).

% tato klauzule se musí spustit první, aby se načetla potřebná fakta
nactiDatabazi :- process_file('pravidla').                                                         % soubor pravidel pro přepis

% hlavní klauzule, spouští se po 'nactiDatabazi', bere text ze vstupu a hned ho přepisuje
transkripce :- findall([D, N], (abbr(D, N)), Zs),                                                  % načtení zkratek do seznamu
               findall([D, N], (change(D, N)), Ds),                                                % načtení pravidel do seznamu
               seeing(StarySoubor),
               see('user'),
               repeat,
                  read(Vstup),                                                                     % "Bon jour".
                  mezeraNaKonec(Vstup, M),                                                         % přidá mezeru na konec textu
                  toLower(M, O),                                                                   % všechna písmena na malá
                  parsujNaCisla(O, Q),                                                             % přepis čísel na text
                  zpracujList(Zs, Q, P),                                                           % přepis textu na fonémy
                  zpracujList(Ds, P, T),                                                           % přepis textu na fonémy
                  toLower(T, T1),                                                                  % zpátky na malá písmena
                  name(T2, T1),                                                                    % a seznam do řetězce
                  write(T2), nl,
                  Vstup == end_of_file,
               !,
               seen,
               see(StarySoubor).

% le noir parler, parler noir ou langue noire (en anglais) est une des langues construites concues par lecrivain et philologue.
