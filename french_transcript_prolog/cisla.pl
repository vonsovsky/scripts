cisla(0, '').
cisla(1, 'un').
cisla(2, 'deux').
cisla(3, 'trois').
cisla(4, 'quatre').
cisla(5, 'cinq').
cisla(6, 'six').
cisla(7, 'sept').
cisla(8, 'huit').
cisla(9, 'neuf').
cisla(10, 'dix').
cisla(11, 'onze').
cisla(12, 'douze').
cisla(13, 'treize').
cisla(14, 'quatorze').
cisla(15, 'quinze').
cisla(16, 'seize').
cisla(17, 'dix-sept').
cisla(18, 'dix-huit').
cisla(19, 'dix-neuf').
cisla(20, 'vingt').
cisla(30, 'trente').
cisla(40, 'quarante').
cisla(50, 'cinquante').
cisla(60, 'soixante').

% replaceal("12", "34", "this12is12a12string", X0), atom_codes(XA,X0).
replaceal(_, _, [], []) :- !.
replaceal(X, Y, Lx, Lyj) :- matchR(X, Lx, LxN), !, replaceal(X, Y, LxN, Lyi), !, append(Y, Lyi, Lyj).
replaceal(X, Y, [H|Lx], [H|Ly]) :- !, replaceal(X, Y, Lx, Ly).

% Match and Replace...
matchR([], X, X) :- !.
matchR([H|L1], [H|L2], X) :- !, matchR(L1, L2, X).

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

tisice(S, B) :- S // 100 > 0, !,
                Stovky is S // 100, DesitkyJednotky is S - S // 100 * 100,
                zpracujDesitkyJednotky(DesitkyJednotky, D), name(D, DJ),
                cisla(Stovky, S1), name(S1, S2),
                (Stovky =\= 1, DesitkyJednotky =:= 0, append(S2, " cents ", S3);
                                                      append(S2, " cent ", S3)),
                !, append(S3, DJ, B).
tisice(S, B) :- DesitkyJednotky is S - S // 100 * 100,
                zpracujDesitkyJednotky(DesitkyJednotky, D), name(D, B).

cislo("0", "zero").
cislo(S, B) :- name(S1, S), cisloA(S1, 0, B).
cisloA(S, T, B) :- S // 1000 > 0, Tisice is S - S // 1000 * 1000,
                   T1 is T + 1, S1 is S // 1000, cisloA(S1, T1, B1),
                   (T1 =:= 1, append(B1, " mille ", B2); T1 =:= 2, append(B1, " million ", B2); append(B1, " milliard ", B2)),
                   !, tisice(Tisice, B3), append(B2, B3, B).
cisloA(S, _, B) :- tisice(S, B).

reverse([],[]).
reverse([X|R], S) :- reverse(R, Zbytek), append(Zbytek, [X], S).

posledniZnak(X, S) :- reverse(X, [S|_]).

% načte celé jedno číslo až do nečíselného symbolu 
parsujCislo([], []).
parsujCislo([X|XS], [X|S]) :- (X >= "0", X =< "9"; X =:= " "),
                              !, parsujCislo(XS, S).
parsujCislo(_, []).

parsujNaCisla([], []).
parsujNaCisla([X|XS], S) :- X >= "0", X =< "9",
                     parsujCislo([X|XS], S1),
                     !,
                     replaceal(" ", "", S1, T),
                     cislo(T, TR),
                     posledniZnak(S1, P),
                     (P =:= 32, append(TR, " ", TS), replaceal(S1, TS, [X|XS], S3);
                                                     replaceal(S1, TR, [X|XS], S3)),
                     !, parsujNaCisla(S3, S).
parsujNaCisla([X|XS], [X|S]) :- parsujNaCisla(XS, S).

zpracuj :- parsujNaCisla("302. abcd 1 02 4 047 cde 2247 fgh 987 432", S), name(S1, S), write(S1).
